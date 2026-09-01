<?php

namespace App\Livewire\Admin;

use App\Livewire\Traits\AttendanceDetailTrait;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Laravel\Jetstream\InteractsWithBanner;
use Livewire\Component;
use Livewire\WithPagination;

class AttendanceComponent extends Component
{
    use AttendanceDetailTrait;
    use InteractsWithBanner, WithPagination;

    // filter
    public ?string $month;

    public ?string $week = null;

    public ?string $date = null;

    public ?string $division = null;

    public ?string $jobTitle = null;

    public ?string $search = null;

    public bool $editingAttendance = false;
    public ?int $editingAttendanceId = null;
    public ?string $attendanceTimeIn = null;
    public ?string $attendanceTimeOut = null;
    public ?string $attendanceStatus = null;
    public ?string $attendanceNote = null;
    public ?string $attendanceUserId = null;
    public ?string $attendanceDate = null;
    public ?int $attendanceShiftId = null;
    public ?int $attendanceBarcodeId = null;
    public ?string $attendanceLatitude = null;
    public ?string $attendanceLongitude = null;

    public function mount()
    {
        $this->date = date('Y-m-d');
    }

    public function editAttendance(int $id): void
    {
        abort_unless(Auth::user()?->isSuperadmin, 403);

        $attendance = Attendance::findOrFail($id);
        $this->resetValidation();
        $this->editingAttendanceId = $attendance->id;
        $this->attendanceTimeIn = $attendance->time_in?->format('H:i');
        $this->attendanceTimeOut = $attendance->time_out?->format('H:i');
        $this->attendanceStatus = $attendance->status;
        $this->attendanceNote = $attendance->note;
        $this->attendanceUserId = $attendance->user_id;
        $this->attendanceDate = $attendance->date?->format('Y-m-d');
        $this->attendanceShiftId = $attendance->shift_id;
        $this->attendanceBarcodeId = $attendance->barcode_id;
        $this->attendanceLatitude = $attendance->latitude;
        $this->attendanceLongitude = $attendance->longitude;
        $this->editingAttendance = true;
    }

    public function updateAttendance(): void
    {
        abort_unless(Auth::user()?->isSuperadmin, 403);

        $validated = $this->validate([
            'editingAttendanceId' => ['required', 'integer', 'exists:attendances,id'],
            'attendanceTimeIn' => ['nullable', 'date_format:H:i'],
            'attendanceTimeOut' => ['nullable', 'date_format:H:i'],
            'attendanceStatus' => ['required', 'in:present,late,excused,sick,absent,incomplete'],
            'attendanceNote' => ['nullable', 'string', 'max:255'],
            'attendanceUserId' => ['required', 'exists:users,id'],
            'attendanceDate' => ['required', 'date_format:Y-m-d'],
            'attendanceShiftId' => ['nullable', 'exists:shifts,id'],
            'attendanceBarcodeId' => ['nullable', 'exists:barcodes,id'],
            'attendanceLatitude' => ['nullable', 'numeric'],
            'attendanceLongitude' => ['nullable', 'numeric'],
        ]);

        $attendance = Attendance::findOrFail($validated['editingAttendanceId']);
        $attendance->update([
            'user_id' => $validated['attendanceUserId'],
            'date' => $validated['attendanceDate'],
            'time_in' => $validated['attendanceTimeIn'],
            'time_out' => $validated['attendanceTimeOut'],
            'status' => $validated['attendanceStatus'],
            'note' => $validated['attendanceNote'],
            'shift_id' => $validated['attendanceShiftId'],
            'barcode_id' => $validated['attendanceBarcodeId'],
            'latitude' => $validated['attendanceLatitude'],
            'longitude' => $validated['attendanceLongitude'],
        ]);
        Attendance::clearUserAttendanceCache($attendance->user, Carbon::parse($attendance->date));

        $this->editingAttendance = false;
        $this->editingAttendanceId = null;
        $this->banner(__('Updated successfully.'));
    }

    public function updating($key): void
    {
        if ($key === 'search' || $key === 'division' || $key === 'jobTitle') {
            $this->resetPage();
        }
        if ($key === 'month') {
            $this->resetPage();
            $this->week = null;
            $this->date = null;
        }
        if ($key === 'week') {
            $this->resetPage();
            $this->month = null;
            $this->date = null;
        }
        if ($key === 'date') {
            $this->resetPage();
            $this->month = null;
            $this->week = null;
        }
    }

    public function render()
    {
        try {
            if ($this->date) {
                $dates = [Carbon::parse($this->date)];
            } elseif ($this->week) {
                $start = Carbon::parse($this->week)->startOfWeek();
                $end = Carbon::parse($this->week)->endOfWeek();
                $dates = $start->range($end)->toArray();
            } elseif ($this->month) {
                $start = Carbon::parse($this->month)->startOfMonth();
                $end = Carbon::parse($this->month)->endOfMonth();
                $dates = $start->range($end)->toArray();
            } else {
                $this->date = date('Y-m-d');
                $dates = [Carbon::parse($this->date)];
            }
        } catch (\Exception $e) {
            // If parsing fails, use fallback if available, otherwise default to today
            if (session()->has('attendance_employees_fallback')) {
                return view('livewire.admin.attendance', session('attendance_employees_fallback'));
            }

            $this->date = date('Y-m-d');
            $dates = [Carbon::parse($this->date)];
        }

        $employees = User::where('group', 'user')
            ->when($this->search, function (Builder $q) {
                return $q->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('nip', 'like', '%'.$this->search.'%');
            })
            ->when($this->division, fn (Builder $q) => $q->where('division_id', $this->division))
            ->when($this->jobTitle, fn (Builder $q) => $q->where('job_title_id', $this->jobTitle))
            ->paginate(20)->through(function (User $user) {
                if ($this->date) {
                    $attendances = new Collection(Cache::remember(
                        "attendance-$user->id-$this->date",
                        now()->addSeconds(5),
                        function () use ($user) {
                            /** @var Collection<Attendance> */
                            $attendances = Attendance::filter(
                                userId: $user->id,
                                date: $this->date,
                            )->get();

                            return $attendances->map(
                                function (Attendance $v) {
                                    $v->setAttribute('coordinates', $v->lat_lng);
                                    $v->setAttribute('lat', $v->latitude);
                                    $v->setAttribute('lng', $v->longitude);
                                    if ($v->attachment) {
                                        $v->setAttribute('attachment', $v->attachment_url);
                                    }
                                    if ($v->shift) {
                                        $v->setAttribute('shift', $v->shift->name);
                                    }

                                    return $v->getAttributes();
                                }
                            )->toArray();
                        }
                    ) ?? []);
                } elseif ($this->week) {
                    $attendances = new Collection(Cache::remember(
                        "attendance-$user->id-$this->week",
                        now()->addSeconds(5),
                        function () use ($user) {
                            /** @var Collection<Attendance> */
                            $attendances = Attendance::filter(
                                userId: $user->id,
                                week: $this->week,
                            )->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note']);

                            return $attendances->map(
                                function (Attendance $v) {
                                    $v->setAttribute('coordinates', $v->lat_lng);
                                    $v->setAttribute('lat', $v->latitude);
                                    $v->setAttribute('lng', $v->longitude);
                                    if ($v->attachment) {
                                        $v->setAttribute('attachment', $v->attachment_url);
                                    }

                                    return $v->getAttributes();
                                }
                            )->toArray();
                        }
                    ) ?? []);
                } elseif ($this->month) {
                    $my = Carbon::parse($this->month);
                    $attendances = new Collection(Cache::remember(
                        "attendance-$user->id-$my->month-$my->year",
                        now()->addSeconds(5),
                        function () use ($user) {
                            /** @var Collection<Attendance> */
                            $attendances = Attendance::filter(
                                month: $this->month,
                                userId: $user->id,
                            )->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note']);

                            return $attendances->map(
                                function (Attendance $v) {
                                    $v->setAttribute('coordinates', $v->lat_lng);
                                    $v->setAttribute('lat', $v->latitude);
                                    $v->setAttribute('lng', $v->longitude);
                                    if ($v->attachment) {
                                        $v->setAttribute('attachment', $v->attachment_url);
                                    }

                                    return $v->getAttributes();
                                }
                            )->toArray();
                        }
                    ) ?? []);
                } else {
                    /** @var Collection */
                    $attendances = Attendance::where('user_id', $user->id)
                        ->get(['id', 'status', 'date', 'latitude', 'longitude', 'attachment', 'note']);
                }
                $user->attendances = $attendances;

                return $user;
            });

        $users = User::where('group', 'user')->orderBy('name')->get(['id', 'name']);
        $shifts = \App\Models\Shift::orderBy('name')->get(['id', 'name']);
        $barcodes = \App\Models\Barcode::orderBy('name')->get(['id', 'name']);

        $data = [
            'employees' => $employees,
            'dates' => $dates,
            'users' => $users,
            'shifts' => $shifts,
            'barcodes' => $barcodes,
        ];
        session(['attendance_employees_fallback' => $data]);

        return view('livewire.admin.attendance', $data);
    }
}
