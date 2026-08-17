@php
  $date = Carbon\Carbon::now();
  $statusChartData = [
      __("status_present") => $presentCount,
      __("status_late") => $lateCount,
      __("status_excused") => $excusedCount,
      __("status_sick") => $sickCount,
      __("status_incomplete") => $incompleteCount,
      __("status_absent") => $absentCount,
  ];
@endphp
<div>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce
  @pushOnce('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
  @endpushOnce

  @if ($isTodayHoliday)
    <div class="flex items-start gap-3 rounded-3xl bg-rose-50 p-5 ring-1 ring-inset ring-rose-200 dark:bg-rose-900/30 dark:ring-rose-800">
      <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-rose-100 dark:bg-rose-900">
        <x-heroicon-o-calendar-days class="h-5 w-5 text-rose-600 dark:text-rose-300" />
      </div>
      <div>
        <h3 class="text-sm font-bold text-rose-700 dark:text-rose-200">Hari ini libur</h3>
        <p class="mt-0.5 text-sm font-semibold text-rose-600 dark:text-rose-300">{{ $todayHolidayName }}</p>
        <p class="mt-1 text-xs text-rose-500 dark:text-rose-400">Absensi otomatis nonaktif hari ini. Status seluruh karyawan ditandai libur.</p>
      </div>
    </div>
  @endif

  <div class="flex flex-col justify-between gap-2 sm:flex-row sm:items-center">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Absensi Hari Ini</h3>
    <span
      class="inline-flex w-fit items-center rounded-full bg-indigo-50 px-4 py-1.5 text-sm font-semibold text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-300">
      {{ __('Employees') }}: {{ $employeesCount }}
    </span>
  </div>

  {{-- Statistik --}}
  <div class="mt-4 grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-5">
    <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-green-100 dark:bg-green-900">
        <x-heroicon-o-check-circle class="h-5 w-5 text-green-600 dark:text-green-300" />
      </div>
      <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $presentCount }}</p>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __("status_present") }}</p>
      <p class="mt-1 text-xs text-amber-500 dark:text-amber-400">{{ __("status_late") }}: {{ $lateCount }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
        <x-heroicon-o-paper-airplane class="h-5 w-5 text-blue-600 dark:text-blue-300" />
      </div>
      <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $excusedCount }}</p>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __("status_excused") }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
        <x-heroicon-o-heart class="h-5 w-5 text-purple-600 dark:text-purple-300" />
      </div>
      <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $sickCount }}</p>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __("status_sick") }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-orange-100 dark:bg-orange-900">
        <x-heroicon-o-clock class="h-5 w-5 text-orange-600 dark:text-orange-300" />
      </div>
      <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $incompleteCount }}</p>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __("status_incomplete") }}</p>
      <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ __("status_incomplete-early") }}</p>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
      <div class="flex h-10 w-10 items-center justify-center rounded-full bg-red-100 dark:bg-red-900">
        <x-heroicon-o-x-circle class="h-5 w-5 text-red-600 dark:text-red-300" />
      </div>
      <p class="mt-3 text-3xl font-bold text-gray-900 dark:text-white">{{ $absentCount }}</p>
      <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __("status_absent") }}</p>
      <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">{{ __("status_absent-alpha") }}</p>
    </div>
  </div>

  @if ($upcomingHolidays->isNotEmpty())
    <div class="mt-4 rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
      <h3 class="text-sm font-bold text-gray-900 dark:text-white">Hari Libur Mendatang</h3>
      <div class="mt-3 flex flex-col gap-2">
        @foreach ($upcomingHolidays as $holiday)
          <div class="flex items-center justify-between rounded-2xl bg-rose-50 px-4 py-3 dark:bg-rose-900/30">
            <div class="flex items-center gap-2">
              <x-heroicon-o-calendar-days class="h-4 w-4 text-rose-500 dark:text-rose-300" />
              <span class="text-sm font-semibold text-gray-800 dark:text-gray-200">{{ $holiday->name }}</span>
            </div>
            <span class="text-xs font-medium text-rose-600 dark:text-rose-300">{{ $holiday->date->translatedFormat('d M Y') }}</span>
          </div>
        @endforeach
      </div>
    </div>
  @endif

  {{-- Grafik --}}
  <div class="mt-4 grid grid-cols-1 gap-3 lg:grid-cols-5">
    <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800 lg:col-span-2">
      <h3 class="text-sm font-bold text-gray-900 dark:text-white">Distribusi Status Hari Ini</h3>
      <div class="mx-auto mt-2 h-56 w-full max-w-xs">
        <canvas id="statusChart"></canvas>
      </div>
    </div>
    <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800 lg:col-span-3">
      <h3 class="text-sm font-bold text-gray-900 dark:text-white">Kehadiran 7 Hari Terakhir</h3>
      <div class="mt-4 h-56 w-full">
        <canvas id="trendChart"></canvas>
      </div>
    </div>
  </div>

  {{-- CTA Pengajuan Izin --}}
  <div class="mt-4">
    <a href="{{ route('admin.leave-requests') }}"
      class="flex items-center justify-between rounded-3xl bg-indigo-600 p-5 text-white shadow-md shadow-indigo-600/30 transition hover:bg-indigo-700 dark:shadow-indigo-900/40">
      <div class="flex items-center gap-3">
        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-white/20">
          <x-heroicon-o-envelope-open class="h-5 w-5" />
        </div>
        <div>
          <p class="text-sm font-bold">Pengajuan Izin Menunggu</p>
          <p class="text-xs text-indigo-100">Klik untuk mengelola pengajuan</p>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <span
          class="rounded-full bg-white px-3 py-1 text-sm font-bold text-indigo-700">{{ $pendingLeaveCount }}</span>
        <x-heroicon-o-arrow-right class="h-5 w-5" />
      </div>
    </a>
  </div>

  {{-- Tabel --}}
  <div class="mt-4 overflow-hidden rounded-3xl bg-white shadow-sm dark:bg-gray-800">
    <div class="overflow-x-auto">
      <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-gray-50 dark:bg-gray-900">
          <tr>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Name') }}
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('NIP') }}
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Division') }}
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Job Title') }}
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Shift') }}
            </th>
            <th scope="col" class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300">
              Status
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Time In') }}
            </th>
            <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300">
              {{ __('Time Out') }}
            </th>
            <th scope="col" class="relative">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
          @php
            $class = 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white';
          @endphp
          @foreach ($employees as $employee)
            @php
              $attendance = $employee->attendance;
              $timeIn = $attendance ? $attendance?->time_in?->format('H:i:s') : null;
              $timeOut = $attendance ? $attendance?->time_out?->format('H:i:s') : null;
              $isHoliday = \App\Helpers::isHoliday($date);
              $status = ($attendance ?? [
                  'status' => $isHoliday ? 'holiday' : (!$date->isPast() ? '-' : 'absent'),
              ])['status'];
              switch ($status) {
                  case 'present':
                      $shortStatus = 'H';
                      $badge = 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300';
                      break;
                  case 'late':
                      $shortStatus = 'T';
                      $badge = 'bg-amber-100 text-amber-700 dark:bg-amber-900/50 dark:text-amber-300';
                      break;
                  case 'excused':
                      $shortStatus = 'I';
                      $badge = 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300';
                      break;
                  case 'sick':
                      $shortStatus = 'S';
                      $badge = 'bg-purple-100 text-purple-700 dark:bg-purple-900/50 dark:text-purple-300';
                      break;
                  case 'incomplete':
                      $shortStatus = 'TT';
                      $badge = 'bg-orange-100 text-orange-700 dark:bg-orange-900/50 dark:text-orange-300';
                      break;
                  case 'absent':
                      $shortStatus = 'A';
                      $badge = 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-300';
                      break;
                  case 'holiday':
                      $shortStatus = 'L';
                      $badge = 'bg-rose-100 text-rose-700 dark:bg-rose-900/50 dark:text-rose-300';
                      break;
                  default:
                      $shortStatus = '-';
                      $badge = 'bg-gray-100 text-gray-500 dark:bg-gray-700 dark:text-gray-400';
                      break;
              }
            @endphp
            <tr wire:key="{{ $employee->id }}" class="transition hover:bg-gray-50 dark:hover:bg-gray-700/50">
              <td class="{{ $class }} text-nowrap">{{ $employee->name }}</td>
              <td class="{{ $class }}">{{ $employee->nip }}</td>
              <td class="{{ $class }} text-nowrap">{{ $employee->division?->name ?? '-' }}</td>
              <td class="{{ $class }} text-nowrap">{{ $employee->jobTitle?->name ?? '-' }}</td>
              <td class="{{ $class }} text-nowrap">{{ $attendance->shift?->name ?? '-' }}</td>
              <td class="px-4 py-3 text-center">
                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-xs font-semibold {{ $badge }}">
                  <span class="h-1.5 w-1.5 rounded-full bg-current"></span>
                  {{ $status != '-' ? __("status_{$status}") : $status }}
                </span>
              </td>
              <td class="{{ $class }}">{{ $timeIn ?? '-' }}</td>
              <td class="{{ $class }}">{{ $timeOut ?? '-' }}</td>
              <td class="px-4 py-3 text-center">
                <div class="flex items-center justify-center gap-3">
                  @if ($attendance && ($attendance->attachment || $attendance->note || $attendance->lat_lng))
                    <button type="button" wire:click="show({{ $attendance->id }})"
                      onclick="setLocation({{ $attendance->latitude ?? 0 }}, {{ $attendance->longitude ?? 0 }})"
                      class="rounded-xl bg-indigo-50 px-4 py-2 text-xs font-semibold text-indigo-600 transition hover:bg-indigo-100 dark:bg-indigo-900/50 dark:text-indigo-300 dark:hover:bg-indigo-900">
                      {{ __('Detail') }}
                    </button>
                  @else
                    -
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <div class="border-t border-gray-200 px-4 py-3 dark:border-gray-700">
      {{ $employees->links() }}
    </div>
  </div>

  <x-attendance-detail-modal :current-attendance="$currentAttendance" />
  @stack('attendance-detail-scripts')

  @pushOnce('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const labelColor = isDark ? '#9ca3af' : '#6b7280';

        const statusData = @json($statusChartData);

        const statusCtx = document.getElementById('statusChart');
        if (statusCtx) {
          new Chart(statusCtx, {
            type: 'doughnut',
            data: {
              labels: Object.keys(statusData),
              datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#22c55e', '#f59e0b', '#3b82f6', '#a855f7', '#f97316', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 6,
              }],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: '65%',
              plugins: {
                legend: {
                  position: 'bottom',
                  labels: {
                    color: labelColor,
                    boxWidth: 10,
                    boxHeight: 10,
                    padding: 12,
                    font: { size: 11 },
                  },
                },
              },
            },
          });
        }

        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
          new Chart(trendCtx, {
            type: 'bar',
            data: {
              labels: @json($labels7Days),
              datasets: [{
                label: 'Hadir',
                data: @json($last7Days),
                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                borderRadius: 8,
                maxBarThickness: 42,
              }],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              plugins: {
                legend: { display: false },
              },
              scales: {
                x: {
                  grid: { display: false },
                  ticks: { color: labelColor, font: { size: 11 } },
                },
                y: {
                  beginAtZero: true,
                  ticks: {
                    color: labelColor,
                    precision: 0,
                    font: { size: 11 },
                  },
                  grid: {
                    color: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)',
                  },
                },
              },
            },
          });
        }
      });
    </script>
  @endpushOnce
</div>