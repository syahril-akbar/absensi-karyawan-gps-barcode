@php
  use Illuminate\Support\Carbon;
  $showUserDetail = !$month || $week || $date; // is week or day filter
  $isPerDayFilter = isset($date);
@endphp
<div>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce

  {{-- Filter card --}}
  <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800 sm:p-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
      <h3 class="text-sm font-bold text-gray-900 dark:text-white">Filter Rekap Absensi</h3>
      <x-secondary-button
        href="{{ route('admin.attendances.report', ['month' => $month, 'week' => $week, 'date' => $date, 'division' => $division, 'jobTitle' => $jobTitle]) }}"
        class="w-full justify-center gap-2 md:w-auto">
        <x-heroicon-o-printer class="h-4 w-4" />
        Cetak Laporan
      </x-secondary-button>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
      <div>
        <x-label for="month_filter" value="Per Bulan" />
        <x-input type="month" name="month_filter" id="month_filter" wire:model.live="month"
          class="mt-2 block w-full rounded-xl" />
      </div>
      <div>
        <x-label for="week_filter" value="Per Minggu" />
        <x-input type="week" name="week_filter" id="week_filter" wire:model.live="week"
          class="mt-2 block w-full rounded-xl" />
      </div>
      <div>
        <x-label for="day_filter" value="Per Hari" />
        <x-input type="date" name="day_filter" id="day_filter" wire:model.live="date"
          class="mt-2 block w-full rounded-xl" />
      </div>
      <div>
        <x-label for="division" value="Divisi" />
        <x-select id="division" wire:model.live="division" class="mt-2 block w-full rounded-xl">
          <option value="">{{ __('Select Division') }}</option>
          @foreach (App\Models\Division::all() as $_division)
            <option value="{{ $_division->id }}" {{ $_division->id == $division ? 'selected' : '' }}>
              {{ $_division->name }}
            </option>
          @endforeach
        </x-select>
      </div>
      <div>
        <x-label for="jobTitle" value="Jabatan" />
        <x-select id="jobTitle" wire:model.live="jobTitle" class="mt-2 block w-full rounded-xl">
          <option value="">{{ __('Select Job Title') }}</option>
          @foreach (App\Models\JobTitle::all() as $_jobTitle)
            <option value="{{ $_jobTitle->id }}" {{ $_jobTitle->id == $jobTitle ? 'selected' : '' }}>
              {{ $_jobTitle->name }}
            </option>
          @endforeach
        </x-select>
      </div>
      <div>
        <x-label for="seacrh" value="Pencarian" />
        <div class="mt-2 flex items-center gap-2">
          <x-input type="text" class="w-full rounded-xl" name="search" id="seacrh" wire:model="search"
            placeholder="{{ __('Search') }}" />
          <x-button type="button" wire:click="$refresh" wire:loading.attr="disabled" class="shrink-0">
            {{ __('Search') }}
          </x-button>
          @if ($search)
            <x-secondary-button type="button" wire:click="$set('search', '')" wire:loading.attr="disabled"
              class="shrink-0">
              {{ __('Reset') }}
            </x-secondary-button>
          @endif
        </div>
      </div>
    </div>
  </div>

  {{-- Tabel --}}
  <div class="mt-5 overflow-hidden rounded-3xl bg-white shadow-sm dark:bg-gray-800">
    <div class="overflow-x-auto">
      <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
        <thead class="bg-indigo-50 dark:bg-gray-900">
          <tr>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              {{ $showUserDetail ? __('Name') : __('Name') . '/' . __('Date') }}
            </th>
            @if ($showUserDetail)
              <th scope="col"
                class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                {{ __('NIP') }}
              </th>
              <th scope="col"
                class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                {{ __('Division') }}
              </th>
              <th scope="col"
                class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                {{ __('Job Title') }}
              </th>
              @if ($isPerDayFilter)
                <th scope="col"
                  class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                  {{ __('Shift') }}
                </th>
              @endif
            @endif
            @foreach ($dates as $date)
              @php
                if (!$isPerDayFilter && $date->isSunday()) {
                    // Minggu merah
                    $textClass = 'text-red-500 dark:text-red-300';
                } elseif (!$isPerDayFilter && $date->isFriday()) {
                    // Jumat hijau
                    $textClass = 'text-green-500 dark:text-green-300';
                } else {
                    $textClass = 'text-gray-500 dark:text-gray-300';
                }
              @endphp
              <th scope="col"
                class="{{ $textClass }} text-nowrap border-l border-gray-200 px-1 py-3 text-center text-xs font-bold dark:border-gray-700">
                @if ($isPerDayFilter)
                  Status
                @else
                  {{ $date->format('d/m') }}
                @endif
              </th>
            @endforeach
            @if ($isPerDayFilter)
              <th scope="col"
                class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                {{ __('Time In') }}
              </th>
              <th scope="col"
                class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
                {{ __('Time Out') }}
              </th>
            @endif
            @if (!$isPerDayFilter)
              @foreach (['H', 'T', 'I', 'S', 'TT', 'A'] as $_st)
                <th scope="col"
                  class="text-nowrap border-l border-gray-200 px-1 py-3 text-center text-xs font-bold text-gray-500 dark:border-gray-700 dark:text-gray-300">
                  {{ $_st }}
                </th>
              @endforeach
            @endif
            @if ($isPerDayFilter)
              <th scope="col" class="relative">
                <span class="sr-only">Actions</span>
              </th>
            @endif
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
          @php
            $class = 'px-4 py-3 text-sm font-medium text-gray-900 dark:text-white';
          @endphp
          @foreach ($employees as $employee)
            @php
              $attendances = $employee->attendances;
            @endphp
            <tr wire:key="{{ $employee->id }}" class="group">
              {{-- Detail karyawan --}}
              <td class="{{ $class }} text-nowrap group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                {{ $employee->name }}
              </td>
              @if ($showUserDetail)
                <td class="{{ $class }} group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                  {{ $employee->nip }}
                </td>
                <td class="{{ $class }} text-nowrap group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                  {{ $employee->division?->name ?? '-' }}
                </td>
                <td class="{{ $class }} text-nowrap group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                  {{ $employee->jobTitle?->name ?? '-' }}
                </td>
                @if ($isPerDayFilter)
                  @php
                    $attendance = $employee->attendances->isEmpty() ? null : $employee->attendances->first();
                    $timeIn = $attendance ? $attendance['time_in'] : null;
                    $timeOut = $attendance ? $attendance['time_out'] : null;
                  @endphp
                  <td class="{{ $class }} text-nowrap group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                    {{ $attendance['shift'] ?? '-' }}
                  </td>
                @endif
              @endif

              {{-- Absensi --}}
              @php
                $presentCount = 0;
                $lateCount = 0;
                $excusedCount = 0;
                $sickCount = 0;
                $absentCount = 0;
                $incompleteCount = 0;
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
                          $bgColor =
                              'bg-green-100 dark:bg-green-900/60 hover:bg-green-200 dark:hover:bg-green-800 text-green-700 dark:text-green-200';
                          $presentCount++;
                          break;
                      case 'late':
                          $shortStatus = 'T';
                          $bgColor =
                              'bg-amber-100 dark:bg-amber-900/60 hover:bg-amber-200 dark:hover:bg-amber-800 text-amber-700 dark:text-amber-200';
                          $lateCount++;
                          break;
                      case 'excused':
                          $shortStatus = 'I';
                          $bgColor =
                              'bg-blue-100 dark:bg-blue-900/60 hover:bg-blue-200 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-200';
                          $excusedCount++;
                          break;
                      case 'sick':
                          $shortStatus = 'S';
                          $bgColor =
                              'bg-purple-100 dark:bg-purple-900/60 hover:bg-purple-200 dark:hover:bg-purple-800 text-purple-700 dark:text-purple-200';
                          $sickCount++;
                          break;
                      case 'incomplete':
                          $shortStatus = 'TT';
                          $bgColor =
                              'bg-orange-100 dark:bg-orange-900/60 hover:bg-orange-200 dark:hover:bg-orange-800 text-orange-700 dark:text-orange-200';
                          $incompleteCount++;
                          break;
                      case 'absent':
                          $shortStatus = 'A';
                          $bgColor =
                              'bg-red-100 dark:bg-red-900/60 hover:bg-red-200 dark:hover:bg-red-800 text-red-700 dark:text-red-200';
                          $absentCount++;
                          break;
                      case 'holiday':
                          $shortStatus = 'L';
                          $bgColor =
                              'bg-rose-100 dark:bg-rose-900/60 hover:bg-rose-200 dark:hover:bg-rose-800 text-rose-700 dark:text-rose-200';
                          break;
                      default:
                          $shortStatus = '-';
                          $bgColor =
                              'hover:bg-gray-100 dark:hover:bg-gray-700';
                          break;
                  }
                @endphp
                @if (!$isPerDayFilter && $attendance && ($attendance['attachment'] || $attendance['note'] || $attendance['coordinates']))
                  <td class="{{ $bgColor }} cursor-pointer text-center text-sm font-semibold">
                    <button class="w-full px-1 py-3" wire:click="show({{ $attendance['id'] }})"
                      onclick="setLocation({{ $attendance['lat'] ?? 0 }}, {{ $attendance['lng'] ?? 0 }})">
                      {{ $isPerDayFilter && $status != '-' ? __("status_{$status}") : $shortStatus }}
                    </button>
                  </td>
                @else
                  <td
                    class="{{ $bgColor }} text-nowrap cursor-pointer px-1 py-3 text-center text-sm font-semibold">
                    {{ $isPerDayFilter && $status != '-' ? __("status_{$status}") : $shortStatus }}
                  </td>
                @endif
              @endforeach

              {{-- Waktu masuk/keluar --}}
              @if ($isPerDayFilter)
                <td class="{{ $class }} group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                  {{ $timeIn ?? '-' }}
                </td>
                <td class="{{ $class }} group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                  {{ $timeOut ?? '-' }}
                </td>
              @endif

              {{-- Total --}}
              @if (!$isPerDayFilter)
                @foreach ([$presentCount, $lateCount, $excusedCount, $sickCount, $incompleteCount, $absentCount] as $statusCount)
                  <td
                    class="cursor-pointer border-l border-gray-200 px-1 py-3 text-center text-sm font-semibold text-gray-700 group-hover:bg-gray-50 dark:border-gray-700 dark:text-gray-300 dark:group-hover:bg-gray-700">
                    {{ $statusCount }}
                  </td>
                @endforeach
              @endif

              {{-- Action --}}
              @if ($isPerDayFilter)
                @php
                  $attendance = $employee->attendances->isEmpty() ? null : $employee->attendances->first();
                @endphp
                <td class="text-center group-hover:bg-gray-50 dark:group-hover:bg-gray-700">
                  <div class="flex items-center justify-center gap-3 py-3">
                    @if ($attendance && ($attendance['attachment'] || $attendance['note'] || $attendance['coordinates']))
                      <x-button type="button" wire:click="show({{ $attendance['id'] }})"
                        onclick="setLocation({{ $attendance['lat'] ?? 0 }}, {{ $attendance['lng'] ?? 0 }})">
                        {{ __('Detail') }}
                      </x-button>
                    @else
                      <span class="text-sm text-gray-400">-</span>
                    @endif
                  </div>
                </td>
              @endif
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @if ($employees->isEmpty())
      <div class="px-6 py-12 text-center">
        <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
          <x-heroicon-o-magnifying-glass class="h-7 w-7 text-gray-400 dark:text-gray-500" />
        </div>
        <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Tidak ada data</p>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Coba ubah filter atau kata kunci pencarian.</p>
      </div>
    @endif
    <div class="border-t border-gray-100 px-5 py-4 dark:border-gray-700">
      {{ $employees->links() }}
    </div>
  </div>

  <x-attendance-detail-modal :current-attendance="$currentAttendance" />
  @stack('attendance-detail-scripts')
</div>