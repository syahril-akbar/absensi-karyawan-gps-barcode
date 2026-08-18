<?php

namespace App\Livewire;

use App\Models\Attendance;
use App\Models\Barcode;
use App\Models\Holiday;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Ballen\Distical\Calculator as DistanceCalculator;
use Ballen\Distical\Entities\LatLong;
use Illuminate\Support\Carbon;

class ScanComponent extends Component
{
    public ?Attendance $attendance = null;
    public $shift_id = null;
    public $shifts = null;
    public ?array $currentLiveCoords = null;
    public array $scanResult = []; // ['type' => 'in'|'out', 'time' => 'H:i:s', 'status' => '...']
    public bool $isAbsence = false;
    public bool $isHolidayToday = false;
    public ?string $holidayName = null;
    public bool $isCheckoutMode = false; // true: scanner jalan untuk absen keluar saja

    public function enterCheckoutMode()
    {
        // Hanya boleh kalau sudah absen masuk & belum keluar.
        if ($this->attendance && is_null($this->attendance->time_out)) {
            $this->isCheckoutMode = true;
            $this->resetError();
            return true;
        }
        return false;
    }

    public function resetError()
    {
        $this->dispatch('reset-error');
    }

    public function cancelCheckoutMode()
    {
        $this->isCheckoutMode = false;
        $this->resetError();
    }

    public function scan(string $barcode)
    {
        if ($this->isHolidayToday) {
            return __('Hari ini libur');
        }

        if (is_null($this->currentLiveCoords)) {
            return __('Invalid location');
        }

        // Checkout butuh mode eksplisit untuk cegah salah-scan jadi keluar.
        if ($this->attendance && is_null($this->attendance->time_out) && !$this->isCheckoutMode) {
            return __('Sudah absen masuk. Tekan tombol "Absen Keluar" untuk pulang.');
        }

        // Otomatis pakai shift yang benar untuk hari ini jika lupa/keliru memilih.
        $this->shift_id = $this->resolveShiftIdForToday($this->shift_id);

        if (is_null($this->shift_id)) {
            return __('Invalid shift');
        }

        /** @var Barcode */
        $barcode = Barcode::firstWhere('value', $barcode);
        if (!Auth::check() || !$barcode) {
            return 'Invalid barcode';
        }

        $barcodeLocation = new LatLong($barcode->latLng['lat'], $barcode->latLng['lng']);
        $userLocation = new LatLong($this->currentLiveCoords[0], $this->currentLiveCoords[1]);

        if (($distance = $this->calculateDistance($userLocation, $barcodeLocation)) > $barcode->radius) {
            return __('Location out of range') . ": $distance" . "m. Max: $barcode->radius" . "m";
        }

        /** @var Attendance */
        $existingAttendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))
            ->where('barcode_id', $barcode->id)
            ->first();

        if (!$existingAttendance) {
            $attendance = $this->createAttendance($barcode);
            $this->scanResult = [
                'type' => 'in',
                'time' => $attendance->time_in,
                'status' => $attendance->status,
            ];
        } else {
            $attendance = $existingAttendance;
            $shift = $attendance->shift;
            $now = Carbon::now();
            $status = $attendance->status;

            if ($shift && $shift->end_time && $now->lt($now->copy()->setTimeFromTimeString($shift->end_time))) {
                $status = 'incomplete';
            }

            $attendance->update([
                'time_out' => $now->format('H:i:s'),
                'status' => $status,
            ]);
            $this->scanResult = [
                'type' => 'out',
                'time' => $now->format('H:i:s'),
                'status' => $status,
            ];
        }

        if ($attendance) {
            $this->setAttendance($attendance->fresh());
            $this->isCheckoutMode = false;
            Attendance::clearUserAttendanceCache(Auth::user(), Carbon::parse($attendance->date));
            return true;
        }
    }

    public function calculateDistance(LatLong $a, LatLong $b)
    {
        $distanceCalculator = new DistanceCalculator($a, $b);
        $distanceInMeter = floor($distanceCalculator->get()->asKilometres() * 1000); // convert to meters
        return $distanceInMeter;
    }

    /** @return Attendance */
    public function createAttendance(Barcode $barcode)
    {
        $now = Carbon::now();
        $date = $now->format('Y-m-d');
        $timeIn = $now->format('H:i:s');
        /** @var Shift */
        $shift = Shift::find($this->shift_id);
        $status = Carbon::now()->setTimeFromTimeString($shift->start_time)->lt($now) ? 'late' : 'present';
        return Attendance::create([
            'user_id' => Auth::user()->id,
            'barcode_id' => $barcode->id,
            'date' => $date,
            'time_in' => $timeIn,
            'time_out' => null,
            'shift_id' => $shift->id,
            'latitude' => doubleval($this->currentLiveCoords[0]),
            'longitude' => doubleval($this->currentLiveCoords[1]),
            'status' => $status,
            'note' => null,
            'attachment' => null,
        ]);
    }

    protected function setAttendance(Attendance $attendance)
    {
        $this->attendance = $attendance;
        $this->shift_id = $attendance->shift_id;
        $this->isAbsence = !in_array($attendance->status, ['present', 'late', 'incomplete']);
    }

    public function getAttendance()
    {
        if (is_null($this->attendance)) {
            return null;
        }
        return [
            'time_in' => $this->attendance?->time_in,
            'time_out' => $this->attendance?->time_out,
        ];
    }

    public function mount()
    {
        $this->shifts = Shift::all();
        $this->isHolidayToday = \App\Helpers::isHoliday(Carbon::now());
        $holiday = Holiday::where('date', Carbon::now()->toDateString())->first();
        $this->holidayName = $this->isHolidayToday ? ($holiday?->name ?? __('Weekend')) : null;

        /** @var Attendance */
        $attendance = Attendance::where('user_id', Auth::user()->id)
            ->where('date', date('Y-m-d'))->first();
        if ($attendance) {
            $this->setAttendance($attendance);
        } else {
            $this->shift_id = $this->resolveShiftIdForToday();
        }
    }

    /**
     * Menentukan shift yang paling tepat untuk hari ini.
     * - Prioritas: shift yang hari-nya (days) berisi hari ini.
     * - Jika ada beberapa, ambil yang jam mulainya paling dekat dengan waktu sekarang.
     * - Jika tidak ada shift khusus hari ini, fallback ke shift berlaku setiap hari
     *   (days kosong) paling dekat, lalu semua shift.
     * - Menghormati pilihan manual user selama shift itu sah untuk hari ini.
     */
    protected function resolveShiftIdForToday(?int $selectedId = null): ?int
    {
        $day = Carbon::now()->dayOfWeekIso;
        $nowMinutes = Carbon::now()->hour * 60 + Carbon::now()->minute;
        $shifts = Shift::all();

        $closest = fn ($collection) => $collection
            ->sortBy(fn (Shift $s) => abs($this->minutesSinceMidnight($s->start_time) - $nowMinutes))
            ->first();

        $specific = $shifts->filter(fn (Shift $s) => !empty($s->days) && in_array($day, $s->days));

        if ($specific->isNotEmpty()) {
            if ($selectedId !== null && $specific->contains('id', $selectedId)) {
                return $selectedId;
            }

            return $closest($specific)?->id;
        }

        $everyDay = $shifts->filter(fn (Shift $s) => empty($s->days));
        $pool = $everyDay->isNotEmpty() ? $everyDay : $shifts;

        if ($pool->isEmpty()) {
            return null;
        }

        if ($selectedId !== null && $pool->contains('id', $selectedId)) {
            return $selectedId;
        }

        return $closest($pool)?->id;
    }

    protected function minutesSinceMidnight(string $time): int
    {
        $parts = explode(':', $time);

        return ((int) ($parts[0] ?? 0)) * 60 + ((int) ($parts[1] ?? 0));
    }

    public function render()
    {
        return view('livewire.scan');
    }
}
