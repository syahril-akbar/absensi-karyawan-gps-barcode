<div>
  <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:gap-6">
    @if ($mode != 'import')
      <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800 sm:p-6">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
            <x-heroicon-o-arrow-down-tray class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Ekspor Data Absensi</h3>
        </div>
        <form wire:submit.prevent="export" class="mt-4">
          <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center">
            <x-label for="year" value="Per Tahun" class="lg:w-24" />
            <x-input type="number" min="1970" max="2099" name="year" id="year" wire:model.live="year"
              class="w-full rounded-xl lg:flex-1" />
          </div>
          <div class="mb-4 flex flex-col gap-3 lg:flex-row lg:items-center">
            <x-label for="month" value="Per Bulan" class="lg:w-24" />
            <x-input type="month" name="month" id="month" wire:model.live="month" class="w-full rounded-xl lg:flex-1" />
          </div>
          <x-select id="division" name="division" class="mb-4 block w-full rounded-xl" wire:model.live="division">
            <option value="">{{ __('Select Division') }}</option>
            @foreach (App\Models\Division::all() as $division)
              <option value="{{ $division->id }}">
                {{ $division->name }}
              </option>
            @endforeach
          </x-select>
          <x-select id="jobTitle" name="job_title" class="mb-4 block w-full rounded-xl" wire:model.live="job_title">
            <option value="">{{ __('Select Job Title') }}</option>
            @foreach (App\Models\JobTitle::all() as $jobTitle)
              <option value="{{ $jobTitle->id }}">
                {{ $jobTitle->name }}
              </option>
            @endforeach
          </x-select>
          <x-select id="education" name="education" class="mb-4 block w-full rounded-xl" wire:model.live="education">
            <option value="">{{ __('Select Education') }}</option>
            @foreach (App\Models\Education::all() as $education)
              <option value="{{ $education->id }}">
                {{ $education->name }}
              </option>
            @endforeach
          </x-select>
          <div class="flex flex-col items-center justify-stretch gap-4">
            <x-secondary-button type="button" wire:click="preview" class="w-full justify-center">
              @if ($mode == 'export')
                {{ __('Cancel') }}
              @else
                {{ __('Preview') }}
              @endif
            </x-secondary-button>
            <x-button class="w-full justify-center" wire:loading.attr="disabled">
              {{ __('Export') }}
            </x-button>
          </div>
        </form>
      </div>
    @endif
    @if ($mode != 'export')
      <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800 sm:p-6">
        <div class="flex items-center gap-3">
          <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
            <x-heroicon-o-arrow-up-tray class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Impor Data Absensi</h3>
        </div>
        <form x-data="{ file: null }" wire:submit.prevent="import" method="post" enctype="multipart/form-data"
          class="mt-4">
          @csrf
          <div class="flex flex-wrap items-center gap-3">
            <x-secondary-button type="button" x-on:click.prevent="$refs.file.click()"
              x-text="file ? 'Ganti File' : 'Pilih File dan Pratinjau'">
              Pilih File
            </x-secondary-button>
            <x-secondary-button type="button" x-show="file"
              x-on:click.prevent="$refs.file.files[0] = null; file = null; $wire.$set('file', null)">
              Hapus File
            </x-secondary-button>
            <h5 class="text-sm text-gray-500 dark:text-gray-200" x-text="file ? file.name : 'File Belum Dipilih'"></h5>
            <x-input type="file" class="hidden" name="file" x-ref="file"
              x-on:change="file = $refs.file.files[0]" wire:model.live="file" />
          </div>
          <div class="mt-5 flex items-center justify-stretch">
            <x-danger-button class="w-full justify-center"
              x-text="file ? '{{ __('Import') }} ' + file.name : '{{ __('Import') }}'">
            </x-danger-button>
          </div>
        </form>
      </div>
    @endif
  </div>

  @if ($mode && $previewing)
    <div class="mt-5 overflow-hidden rounded-3xl bg-white shadow-sm dark:bg-gray-800">
      <div class="flex items-center gap-3 px-5 pt-5">
        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
          <x-heroicon-o-magnifying-glass class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
        </div>
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">{{ __('Preview') . ' ' . $mode }}</h3>
      </div>
      <div class="mt-4 w-full overflow-x-auto p-5 pt-0 text-sm">
        @php
          $trClass = 'divide-x divide-gray-200 dark:divide-gray-700';
          $thClass = 'px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300';
          $tdClass = 'px-4 py-4 text-sm font-medium text-gray-900 dark:text-white';
        @endphp
        <table class="w-full divide-y divide-gray-200 border dark:divide-gray-700 dark:border-gray-700">
          <thead class="bg-gray-50 dark:bg-gray-900">
            <tr class="{{ $trClass }}">
              <th scope="col" class="px-2 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">
                No
              </th>
              <th class="{{ $thClass }}">Date</th>
              <th class="{{ $thClass }}">Name</th>
              <th class="{{ $thClass }}">NIP</th>
              <th class="{{ $thClass }} text-nowrap">Time In</th>
              <th class="{{ $thClass }} text-nowrap">Time Out</th>
              <th class="{{ $thClass }}">Shift</th>
              <th class="{{ $thClass }} text-nowrap">Barcode Id</th>
              <th class="{{ $thClass }}">Coordinates</th>
              <th class="{{ $thClass }}">Status</th>
              <th class="{{ $thClass }}">Note</th>
              <th class="{{ $thClass }}">Attachment</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
            @foreach ($attendances as $attendance)
              <tr class="{{ $trClass }}">
                <td class="px-2 py-4 text-center text-sm font-medium text-gray-900 dark:text-white">
                  {{ $loop->iteration }}
                </td>
                <td class="{{ $tdClass }} text-nowrap">{{ $attendance->date?->format('Y-m-d') }}</td>
                <td class="{{ $tdClass }}">{{ $attendance->user?->name }}</td>
                <td class="{{ $tdClass }}">{{ $attendance->user?->nip }}</td>
                <td class="{{ $tdClass }}">{{ $attendance->time_in?->format('H:i:s') }}</td>
                <td class="{{ $tdClass }}">{{ $attendance->time_out?->format('H:i:s') }}</td>
                <td class="{{ $tdClass }} text-nowrap">{{ $attendance->shift?->name }}</td>
                <td class="{{ $tdClass }}">{{ $attendance->barcode_id }}</td>
                <td class="{{ $tdClass }}">
                  {{ $attendance->lat_lng ? $attendance->latitude . ',' . $attendance->longitude : null }}
                </td>
                <td class="{{ $tdClass }} text-nowrap">{{ __("status_" . $attendance->status) }}</td>
                <td class="{{ $tdClass }}">
                  <div class="w-48">{{ Str::limit($attendance->note, 30, '...') }}</div>
                </td>
                <td class="{{ $tdClass }}">
                  <img src="{{ $attendance->attachment }}" class="max-h-48 object-contain">
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif
</div>