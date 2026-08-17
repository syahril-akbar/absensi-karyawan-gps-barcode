<div class="mt-6 flex w-full flex-col gap-5">
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce

  <!-- Month Filter -->
  <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
    <div class="flex items-center justify-between gap-3">
      <div>
        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Pilih Bulan</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400">Klik tanggal untuk lihat detail</p>
      </div>
      <x-input type="month" name="month_filter" id="month_filter" wire:model.live="month"
        class="w-40 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200" />
    </div>
  </div>

  <!-- Calendar Card -->
  <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
    <div class="grid w-full grid-cols-7 text-center">
      @foreach (['M', 'S', 'S', 'R', 'K', 'J', 'S'] as $day)
        <div
          class="flex h-9 items-center justify-center text-xs font-bold {{ $day === 'M' ? 'text-red-400' : '' }} {{ $day === 'J' ? 'text-green-500 dark:text-green-400' : '' }} {{ $day === 'S' || $day === 'M' || $day === 'K' || $day === 'R' ? 'text-gray-400' : 'text-gray-500 dark:text-gray-400' }}">
          {{ $day }}
        </div>
      @endforeach
      @if ($start->dayOfWeek !== 0)
        @foreach (range(1, $start->dayOfWeek) as $i)
          <div class="aspect-square p-1">
            <div class="h-full w-full rounded-xl bg-gray-50 dark:bg-gray-900/40"></div>
          </div>
        @endforeach
      @endif
      @php
        $presentCount = 0;
        $lateCount = 0;
        $excusedCount = 0;
        $sickCount = 0;
        $absentCount = 0;
        $incompleteCount = 0;
        $holidayCount = 0;
      @endphp
      @foreach ($dates as $date)
        @php
          $isHoliday = \App\Helpers::isHoliday($date);
          $attendance = $attendances->firstWhere(fn($v, $k) => $v['date'] === $date->format('Y-m-d'));
          $status = ($attendance ?? [
              'status' => $isHoliday ? 'holiday' : (!$date->isPast() ? '-' : 'absent'),
          ])['status'];

          switch ($status) {
              case 'present':
                  $shortStatus = 'H';
                  $bgColor = 'bg-green-500 text-white dark:bg-green-600';
                  $presentCount++;
                  break;
              case 'late':
                  $shortStatus = 'T';
                  $bgColor = 'bg-amber-400 text-white dark:bg-amber-500';
                  $lateCount++;
                  break;
              case 'excused':
                  $shortStatus = 'I';
                  $bgColor = 'bg-blue-400 text-white dark:bg-blue-600';
                  $excusedCount++;
                  break;
              case 'sick':
                  $shortStatus = 'S';
                  $bgColor = 'bg-purple-400 text-white dark:bg-purple-600';
                  $sickCount++;
                  break;
              case 'incomplete':
                  $shortStatus = 'TT';
                  $bgColor = 'bg-orange-400 text-white dark:bg-orange-600';
                  $incompleteCount++;
                  break;
              case 'absent':
                  $shortStatus = 'A';
                  $bgColor = 'bg-red-500 text-white dark:bg-red-600';
                  $absentCount++;
                  break;
              case 'holiday':
                  $shortStatus = 'L';
                  $bgColor = 'bg-rose-100 text-rose-500 dark:bg-rose-900/30 dark:text-rose-300';
                  $holidayCount++;
                  break;
              default:
                  $shortStatus = '-';
                  $bgColor = 'bg-gray-100 text-gray-400 dark:bg-gray-700 dark:text-gray-500';
                  break;
          }
        @endphp
        @if ($attendance && ($attendance['attachment'] || $attendance['note'] || $attendance['coordinates']))
          <button class="aspect-square p-1" wire:click="show({{ $attendance['id'] }})"
            onclick="setLocation({{ $attendance['lat'] ?? 0 }}, {{ $attendance['lng'] ?? 0 }})">
            <div
              class="flex h-full w-full flex-col items-center justify-center rounded-xl text-xs font-bold transition hover:scale-105 {{ $bgColor }}">
              <span>{{ $date->format('d') }}</span>
              <span class="text-[10px] font-semibold opacity-90">{{ $shortStatus }}</span>
            </div>
          </button>
        @else
          <div class="aspect-square p-1">
            <div
              class="flex h-full w-full flex-col items-center justify-center rounded-xl text-xs font-bold {{ $bgColor }}">
              <span>{{ $date->format('d') }}</span>
              <span class="text-[10px] font-semibold opacity-90">{{ $shortStatus }}</span>
            </div>
          </div>
        @endif
      @endforeach
      @if ($end->dayOfWeek !== 6)
        @foreach (range(5, $end->dayOfWeek) as $i)
          <div class="aspect-square p-1">
            <div class="h-full w-full rounded-xl bg-gray-50 dark:bg-gray-900/40"></div>
          </div>
        @endforeach
      @endif
    </div>
  </div>

  <!-- Legend / Stats -->
  <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Ringkasan Bulan Ini</h3>
    <div class="mt-4 grid grid-cols-2 gap-3">
      <div class="flex items-center justify-between rounded-2xl bg-green-50 px-4 py-3 dark:bg-green-900/30">
        <div class="flex items-center gap-2">
          <span class="flex h-3 w-3 rounded-full bg-green-500"></span>
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Hadir</span>
        </div>
        <span class="text-lg font-bold text-green-600 dark:text-green-400">{{ $presentCount + $lateCount }}</span>
      </div>
      <div class="flex items-center justify-between rounded-2xl bg-amber-50 px-4 py-3 dark:bg-amber-900/30">
        <div class="flex items-center gap-2">
          <span class="flex h-3 w-3 rounded-full bg-amber-400"></span>
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Terlambat</span>
        </div>
        <span class="text-lg font-bold text-amber-500 dark:text-amber-400">{{ $lateCount }}</span>
      </div>
      <div class="flex items-center justify-between rounded-2xl bg-blue-50 px-4 py-3 dark:bg-blue-900/30">
        <div class="flex items-center gap-2">
          <span class="flex h-3 w-3 rounded-full bg-blue-400"></span>
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Izin</span>
        </div>
        <span class="text-lg font-bold text-blue-500 dark:text-blue-400">{{ $excusedCount }}</span>
      </div>
      <div class="flex items-center justify-between rounded-2xl bg-purple-50 px-4 py-3 dark:bg-purple-900/30">
        <div class="flex items-center gap-2">
          <span class="flex h-3 w-3 rounded-full bg-purple-400"></span>
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Sakit</span>
        </div>
        <span class="text-lg font-bold text-purple-500 dark:text-purple-400">{{ $sickCount }}</span>
      </div>
      <div class="flex items-center justify-between rounded-2xl bg-orange-50 px-4 py-3 dark:bg-orange-900/30">
        <div class="flex items-center gap-2">
          <span class="flex h-3 w-3 rounded-full bg-orange-400"></span>
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Tidak Lengkap</span>
        </div>
        <span class="text-lg font-bold text-orange-500 dark:text-orange-400">{{ $incompleteCount }}</span>
      </div>
      <div class="flex items-center justify-between rounded-2xl bg-red-50 px-4 py-3 dark:bg-red-900/30">
        <div class="flex items-center gap-2">
          <span class="flex h-3 w-3 rounded-full bg-red-500"></span>
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Absen</span>
        </div>
        <span class="text-lg font-bold text-red-500 dark:text-red-400">{{ $absentCount }}</span>
      </div>
      <div class="col-span-2 flex items-center justify-between rounded-2xl bg-rose-50 px-4 py-3 dark:bg-rose-900/30">
        <div class="flex items-center gap-2">
          <span class="flex h-3 w-3 rounded-full bg-rose-400"></span>
          <span class="text-xs font-medium text-gray-600 dark:text-gray-300">Libur</span>
        </div>
        <span class="text-lg font-bold text-rose-500 dark:text-rose-400">{{ $holidayCount }}</span>
      </div>
    </div>
  </div>

  <x-attendance-detail-modal :current-attendance="$currentAttendance" />
  @stack('attendance-detail-scripts')
</div>