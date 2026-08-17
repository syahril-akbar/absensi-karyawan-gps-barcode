<div>
  {{-- Header + Aksi --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">
      {{ $holidays->total() }} hari libur terdaftar
      <span class="block text-xs text-gray-400 dark:text-gray-500">Sabtu &amp; Minggu otomatis dihitung libur</span>
    </p>
    <x-button wire:click="showCreating" class="w-full justify-center sm:w-auto">
      <x-heroicon-o-plus class="mr-2 h-4 w-4" />
      Tambah Hari Libur
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
              {{ __('Date') }}
            </th>
            <th scope="col"
              class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
              Hari Libur
            </th>
            <th scope="col" class="relative px-4 py-3">
              <span class="sr-only">Actions</span>
            </th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
          @forelse ($holidays as $holiday)
            <tr wire:key="{{ $holiday->id }}" class="group">
              <td class="px-4 py-4 text-sm font-semibold text-gray-900 dark:text-white">
                <span class="inline-flex items-center gap-2">
                  <span class="flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                    <x-heroicon-o-calendar-days class="h-4 w-4 text-indigo-600 dark:text-indigo-300" />
                  </span>
                  {{ $holiday->date->translatedFormat('l, d F Y') }}
                </span>
              </td>
              <td class="px-4 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $holiday->name }}</td>
              <td class="px-4 py-4">
                <div class="flex justify-end gap-2">
                  <x-button wire:click="edit({{ $holiday->id }})">
                    <x-heroicon-o-pencil-square class="mr-1.5 h-4 w-4" />
                    Edit
                  </x-button>
                  <x-danger-button wire:click="confirmDeletion({{ $holiday->id }}, '{{ $holiday->name }}')">
                    <x-heroicon-o-trash class="h-4 w-4" />
                  </x-danger-button>
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="px-6 py-12 text-center">
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                  <x-heroicon-o-calendar-days class="h-7 w-7 text-gray-400 dark:text-gray-500" />
                </div>
                <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Belum ada hari libur</p>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tambahkan hari libur nasional atau perusahaan.</p>
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  @if ($holidays->hasPages())
    <div class="mt-4 border-t border-gray-100 pt-4 dark:border-gray-700">
      {{ $holidays->links() }}
    </div>
  @endif

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Hari Libur
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
        <x-heroicon-o-calendar-days class="h-5 w-5 text-indigo-600" />
        Hari Libur Baru
      </span>
    </x-slot>

    <form wire:submit="create">
      <x-slot name="content">
        <div>
          <x-label for="date">{{ __('Date') }}</x-label>
          <x-input id="date" class="mt-1 block w-full rounded-xl" type="date" wire:model="form.date"
            required />
          @error('form.date')
            <x-input-error for="form.date" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="mt-4">
          <x-label for="name">Nama Hari Libur</x-label>
          <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.name"
            placeholder="cth: Tahun Baru 2026" />
          @error('form.name')
            <x-input-error for="form.name" class="mt-2" message="{{ $message }}" />
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
        Edit Hari Libur
      </span>
    </x-slot>

    <form wire:submit.prevent="update" id="holiday-edit">
      <x-slot name="content">
        <div>
          <x-label for="date">{{ __('Date') }}</x-label>
          <x-input id="date" class="mt-1 block w-full rounded-xl" type="date" wire:model="form.date"
            required />
          @error('form.date')
            <x-input-error for="form.date" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>
        <div class="mt-4">
          <x-label for="name">Nama Hari Libur</x-label>
          <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="form.name"
            placeholder="cth: Tahun Baru 2026" />
          @error('form.name')
            <x-input-error for="form.name" class="mt-2" message="{{ $message }}" />
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