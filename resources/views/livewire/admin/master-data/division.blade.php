<div>
  {{-- Header + Aksi --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $divisions->count() }} divisi tersedia</p>
    <x-button wire:click="showCreating" class="w-full justify-center sm:w-auto">
      <x-heroicon-o-plus class="mr-2 h-4 w-4" />
      Tambah Divisi
    </x-button>
  </div>

  {{-- Tabel --}}
  <div class="mt-4 overflow-hidden rounded-3xl bg-white shadow-sm dark:bg-gray-800">
    <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
      <thead class="bg-indigo-50 dark:bg-gray-900">
        <tr>
          <th scope="col"
            class="px-4 py-3 text-left text-xs font-bold uppercase tracking-wide text-gray-600 dark:text-gray-300">
            Divisi
          </th>
          <th scope="col" class="relative px-4 py-3">
            <span class="sr-only">Actions</span>
          </th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
        @forelse ($divisions as $division)
          <tr wire:key="{{ $division->id }}" class="group">
            <td class="flex items-center gap-3 px-4 py-4 text-sm font-semibold text-gray-900 dark:text-white">
              <span class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
                <x-heroicon-o-building-office class="h-4 w-4 text-indigo-600 dark:text-indigo-300" />
              </span>
              {{ $division->name }}
            </td>
            <td class="px-4 py-4">
              <div class="flex justify-end gap-2">
                <x-button wire:click="edit({{ $division->id }})">
                  <x-heroicon-o-pencil-square class="mr-1.5 h-4 w-4" />
                  Edit
                </x-button>
                <x-danger-button wire:click="confirmDeletion({{ $division->id }}, '{{ $division->name }}')">
                  <x-heroicon-o-trash class="h-4 w-4" />
                </x-danger-button>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="2" class="px-6 py-12 text-center">
              <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
                <x-heroicon-o-building-office class="h-7 w-7 text-gray-400 dark:text-gray-500" />
              </div>
              <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Belum ada divisi</p>
              <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Tambahkan divisi pertama kamu.</p>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Divisi
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
        <x-heroicon-o-building-office class="h-5 w-5 text-indigo-600" />
        Divisi Baru
      </span>
    </x-slot>

    <form wire:submit="create">
      <x-slot name="content">
        <x-label for="name">Nama Divisi</x-label>
        <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="name"
          placeholder="cth: Divisi Teknologi" />
        @error('name')
          <x-input-error for="name" class="mt-2" message="{{ $message }}" />
        @enderror
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
        Edit Divisi
      </span>
    </x-slot>

    <form wire:submit.prevent="update">
      <x-slot name="content">
        <x-label for="name">Nama Divisi</x-label>
        <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="name"
          placeholder="cth: Divisi Teknologi" />
        @error('name')
          <x-input-error for="name" class="mt-2" message="{{ $message }}" />
        @enderror
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