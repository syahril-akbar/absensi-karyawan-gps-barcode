<div>
  <form wire:submit="save" class="grid grid-cols-1 gap-5">
    {{-- Pendaftaran --}}
    <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
      <div class="flex items-center justify-between gap-4">
        <div>
          <h3 class="text-base font-bold text-gray-900 dark:text-white">Pendaftaran Akun</h3>
          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            Matikan untuk menutup pendaftaran akun baru. Halaman register akan menampilkan
            pemberitahuan "Pendaftaran Ditutup".
          </p>
        </div>
        <label class="inline-flex cursor-pointer items-center">
          <input type="checkbox" class="peer sr-only" wire:model="registration_enabled">
          <div
            class="relative h-7 w-12 rounded-full bg-gray-200 transition peer-checked:bg-indigo-600 after:absolute after:start-1 after:top-1 after:h-5 after:w-5 after:rounded-full after:bg-white after:transition-all peer-checked:after:translate-x-5">
          </div>
        </label>
      </div>

      <div class="mt-5">
        <x-label for="registration_message">Pesan Penutupan Pendaftaran</x-label>
        <textarea id="registration_message" rows="3"
          class="mt-1 block w-full rounded-xl border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300"
          wire:model="registration_message"
          placeholder="Pendaftaran akun sedang ditutup sementara. Silakan hubungi admin untuk informasi lebih lanjut."></textarea>
        @error('registration_message')
          <x-input-error for="registration_message" class="mt-2" />
        @enderror
      </div>
    </div>

    <div>
      <x-button type="submit" class="w-full justify-center sm:w-auto">
        Simpan Pengaturan
      </x-button>
    </div>
  </form>
</div>