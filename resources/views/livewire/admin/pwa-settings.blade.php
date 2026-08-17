<div>
  <form wire:submit="save" class="grid grid-cols-1 gap-5 lg:grid-cols-2">
    {{-- Status PWA --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800 lg:col-span-2">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h3 class="text-base font-bold text-gray-900 dark:text-white">Status PWA</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Aktifkan agar aplikasi bisa diinstall di HP dan punya mode offline ringan.
          </p>
        </div>
        <label class="inline-flex cursor-pointer items-center">
          <input type="checkbox" class="peer sr-only" wire:model="enabled">
          <div
            class="relative h-7 w-12 rounded-full bg-gray-200 transition peer-checked:bg-indigo-600 after:absolute after:start-1 after:top-1 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-5">
          </div>
        </label>
      </div>
    </div>

    {{-- Identitas --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
      <h3 class="text-base font-bold text-gray-900 dark:text-white">Identitas Aplikasi</h3>
      <div class="mt-4 space-y-4">
        <div>
          <x-label for="name">Nama Aplikasi</x-label>
          <x-input id="name" class="mt-1 block w-full rounded-xl" type="text" wire:model="name"
            placeholder="Absensi Karyawan" />
          @error('name')
            <x-input-error for="name" class="mt-2" />
          @enderror
        </div>
        <div>
          <x-label for="short_name">Nama Singkat (di layar utama)</x-label>
          <x-input id="short_name" class="mt-1 block w-full rounded-xl" type="text" wire:model="short_name"
            placeholder="Absensi" maxlength="30" />
          @error('short_name')
            <x-input-error for="short_name" class="mt-2" />
          @enderror
        </div>
        <div>
          <x-label for="description">Deskripsi</x-label>
          <textarea id="description" rows="2"
            class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
            wire:model="description" placeholder="Aplikasi absensi karyawan berbasis GPS & Barcode"></textarea>
          @error('description')
            <x-input-error for="description" class="mt-2" />
          @enderror
        </div>
      </div>
    </div>

    {{-- Tampilan --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
      <h3 class="text-base font-bold text-gray-900 dark:text-white">Tampilan</h3>
      <div class="mt-4 space-y-4">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <x-label for="theme_color">Warna Tema</x-label>
            <div class="mt-1 flex items-center gap-2">
              <input id="theme_color" type="color" value="{{ $theme_color }}"
                class="h-10 w-14 shrink-0 cursor-pointer rounded-lg border border-gray-300 bg-transparent dark:border-gray-700"
                wire:model="theme_color">
              <x-input class="min-w-0 flex-1 rounded-xl" type="text" wire:model="theme_color" placeholder="#4f46e5" />
            </div>
            @error('theme_color')
              <x-input-error for="theme_color" class="mt-2" />
            @enderror
          </div>
          <div>
            <x-label for="background_color">Warna Latar</x-label>
            <div class="mt-1 flex items-center gap-2">
              <input id="background_color" type="color" value="{{ $background_color }}"
                class="h-10 w-14 shrink-0 cursor-pointer rounded-lg border border-gray-300 bg-transparent dark:border-gray-700"
                wire:model="background_color">
              <x-input class="min-w-0 flex-1 rounded-xl" type="text" wire:model="background_color" placeholder="#eef2ff" />
            </div>
            @error('background_color')
              <x-input-error for="background_color" class="mt-2" />
            @enderror
          </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
          <div>
            <x-label for="display">Mode Tampilan</x-label>
            <x-select id="display" class="mt-1 block w-full rounded-xl" wire:model="display">
              <option value="standalone">Standalone (aplikasi penuh)</option>
              <option value="fullscreen">Fullscreen</option>
              <option value="minimal-ui">Minimal UI</option>
              <option value="browser">Browser biasa</option>
            </x-select>
            @error('display')
              <x-input-error for="display" class="mt-2" />
            @enderror
          </div>
          <div>
            <x-label for="orientation">Orientasi</x-label>
            <x-select id="orientation" class="mt-1 block w-full rounded-xl" wire:model="orientation">
              <option value="any">Any</option>
              <option value="portrait">Portrait</option>
              <option value="landscape">Landscape</option>
              <option value="portrait-primary">Portrait (utama)</option>
              <option value="landscape-primary">Landscape (utama)</option>
            </x-select>
            @error('orientation')
              <x-input-error for="orientation" class="mt-2" />
            @enderror
          </div>
        </div>
      </div>
    </div>

    {{-- Info ikon & manifest --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800 lg:col-span-2">
      <h3 class="text-base font-bold text-gray-900 dark:text-white">Informasi</h3>
      <div class="mt-3 space-y-2 text-sm text-gray-500 dark:text-gray-400">
        <p>· Ikon aplikasi (192x192, 512x512) memakai file tetap di <code class="rounded bg-gray-100 px-1 py-0.5 text-xs dark:bg-gray-700">/icons</code>.</p>
        <p>· Manifest dinamis tersedia di <a href="{{ $manifestUrl }}" target="_blank"
            class="font-medium text-indigo-600 hover:underline dark:text-indigo-400">{{ $manifestUrl }}</a>.</p>
        <p>· Setelah mengubah pengaturan, pengguna yang sudah menginstall mungkin perlu menghapus & menginstall ulang agar perubahan tampil.</p>
      </div>
    </div>

    <div class="lg:col-span-2">
      <x-button type="submit" class="w-full justify-center sm:w-auto">
        Simpan Pengaturan
      </x-button>
    </div>
  </form>
</div>