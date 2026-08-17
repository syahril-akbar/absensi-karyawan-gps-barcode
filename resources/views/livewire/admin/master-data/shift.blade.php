<div>
  @php
    $dayLabels = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
  @endphp
  {{-- Header + Aksi --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $shifts->count() }} shift tersedia</p>
    <x-button wire:click="showCreating" class="w-full justify-center sm:w-auto">
      <x-heroicon-o-plus class="mr-2 h-4 w-4" />
      Tambah Shift
    </x-button>
  </div>

  {{-- Tabel --}}
  <div class="mt-4 overflow-hidden rounded-3xl bg-white shadow-sm dark:bg-gray-800">
    <div class="overflow-x-auto">
      <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-indigo-50 dark:bg-gray-900">
        <tr>
          <th scope="col"
            class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
            Shift
          </th>
          <th scope="col"
            class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
            {{ __('Time Start') }}
          </th>
          <th scope="col"
            class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
            {{ __('Time End') }}
          </th>
          <th scope="col"
            class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
            Hari Berlaku
          </th>
          <th scope="col" class="relative px-4 py-3">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @forelse ($shifts as $shift)
          <tr wire:key="{{ $shift->id }}" class="group">
            <td class="px-4 py-4 text-sm font-semibold text-gray-900 dark:text-white">
              <span class="inline-flex items-center gap-2">
                <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                  <x-heroicon-o-clock class="h-4 w-4 text-indigo-600 dark:text-indigo-300" />
                </span>
                {{ $shift->name }}
              </span>
            </td>
            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
              <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700 dark:bg-green-900/60 dark:text-green-200">
                {{ $shift->start_time }}
              </span>
            </td>
            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
              <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700 dark:bg-red-900/60 dark:text-red-200">
                {{ $shift->end_time ?? '-' }}
              </span>
            </td>
            <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">
              @if (empty($shift->days))
                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold text-gray-600 dark:bg-gray-700 dark:text-gray-300">
                  Setiap Hari
                </span>
              @else
                <div class="flex max-w-xs flex-wrap gap-1">
                  @foreach ($dayLabels as $d => $label)
                    @if (in_array($d, $shift->days))
                      <span class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-semibold text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300">
                        {{ $label }}
                      </span>
                    @endif
                  @endforeach
                </div>
              @endif
            </td>
            <td class="px-4 py-4">
              <div class="flex justify-end gap-2">
                <x-button wire:click="edit({{ $shift->id }})">
                  <x-heroicon-o-pencil-square class="mr-1.5 h-4 w-4" />
                  Edit
                </x-button>
                <x-danger-button wire:click="confirmDeletion({{ $shift->id }}, '{{ $shift->name }}')">
                  <x-heroicon-o-trash class="h-4 w-4" />
                </x-danger-button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="px-6 py-12 text-center">
              <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                <x-heroicon-o-clock class="h-7 w-7 text-gray-400 dark:text-gray-500" />
              </div>
              <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Belum ada shift</p>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tambahkan shift pertama kamu.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
    </div>
  </div>

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Shift
    </x-slot>

    <x-slot name="content">
      Apakah Anda yakin ingin menghapus <b>{{ $deleteName }}</b>?
    </x-slot>

    <x-slot name="footer">
      <x-secondary-button wire:click="$toggle('confirmingDeletion')" wire:loading.attr="disabled">
        {{ __('Cancel') }}
      </x-secondary-button>

      <x-danger-button class="ml-2" wire:click="delete" wire:loading.attr="disabled">
        {{ __('Confirm') }}
      </x-danger-button>
    </x-slot>
  </x-confirmation-modal>

  <x-dialog-modal wire:model="creating" maxWidth="md">
    <x-slot name="title">
      <span class="flex items-center gap-2">
        <x-heroicon-o-clock class="h-5 w-5 text-indigo-600" />
        Shift Baru
      </span>
    </x-slot>

    <form wire:submit="create">
      <x-slot name="content">
        <div>
          <x-label for="name">Nama Shift</x-label>
          <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.name"
            placeholder="cth: Shift Pagi" />
          @error('form.name')
            <x-input-error for="form.name" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <x-label for="start_time">{{ __('Time Start') }}</x-label>
            <x-input id="start_time" class="mt-1 block w-full rounded-xl" type="time" wire:model="form.start_time"
              required />
            @error('form.start_time')
              <x-input-error for="form.start_time" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
          <div>
            <x-label for="end_time">{{ __('Time End') }}</x-label>
            <x-input id="end_time" class="mt-1 block w-full rounded-xl" type="time" wire:model="form.end_time" />
            @error('form.end_time')
              <x-input-error for="form.end_time" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>
        <div class="mt-4">
          <x-label for="days">Hari Berlaku</x-label>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika berlaku setiap hari.</p>
          <div class="mt-2 flex flex-wrap gap-2">
            @foreach ($dayLabels as $d => $label)
              <button type="button" wire:click="toggleDay({{ $d }})" wire:key="day-{{ $d }}"
                class="inline-flex cursor-pointer items-center rounded-full border px-3 py-1.5 text-sm font-medium transition {{ in_array($d, $form->days) ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300' : 'border-gray-200 text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:text-gray-300' }}">
                {{ $label }}
              </button>
            @endforeach
          </div>
          @error('form.days')
            <x-input-error for="form.days" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
      </x-slot>

      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('creating')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>

        <x-button class="ml-2" wire:click="create" wire:loading.attr="disabled">
          {{ __('Confirm') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>

  <x-dialog-modal wire:model="editing" maxWidth="md">
    <x-slot name="title">
      <span class="flex items-center gap-2">
        <x-heroicon-o-pencil-square class="h-5 w-5 text-indigo-600" />
        Edit Shift
      </span>
    </x-slot>

    <form wire:submit.prevent="update" id="shift-edit">
      <x-slot name="content">
        <div>
          <x-label for="name">Nama Shift</x-label>
          <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.name"
            placeholder="cth: Shift Pagi" />
          @error('form.name')
            <x-input-error for="form.name" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <x-label for="start_time">{{ __('Time Start') }}</x-label>
            <x-input id="start_time" class="mt-1 block w-full rounded-xl" type="time" wire:model="form.start_time"
              required />
            @error('form.start_time')
              <x-input-error for="form.start_time" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
          <div>
            <x-label for="end_time">{{ __('Time End') }}</x-label>
            <x-input id="end_time" class="mt-1 block w-full rounded-xl" type="time" wire:model="form.end_time" />
            @error('form.end_time')
              <x-input-error for="form.end_time" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>
        <div class="mt-4">
          <x-label for="days">Hari Berlaku</x-label>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Kosongkan jika berlaku setiap hari.</p>
          <div class="mt-2 flex flex-wrap gap-2">
            @foreach ($dayLabels as $d => $label)
              <button type="button" wire:click="toggleDay({{ $d }})" wire:key="day-{{ $d }}"
                class="inline-flex cursor-pointer items-center rounded-full border px-3 py-1.5 text-sm font-medium transition {{ in_array($d, $form->days) ? 'border-indigo-500 bg-indigo-50 text-indigo-700 dark:bg-indigo-500/20 dark:text-indigo-300' : 'border-gray-200 text-gray-600 hover:border-gray-300 dark:border-gray-700 dark:text-gray-300' }}">
                {{ $label }}
              </button>
            @endforeach
          </div>
          @error('form.days')
            <x-input-error for="form.days" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
      </x-slot>

      <x-slot name="footer">
        <x-secondary-button wire:click="$toggle('editing')" wire:loading.attr="disabled">
          {{ __('Cancel') }}
        </x-secondary-button>

        <x-button class="ml-2" wire:click="update" wire:loading.attr="disabled">
          {{ __('Confirm') }}
        </x-button>
      </x-slot>
    </form>
  </x-dialog-modal>
</div>