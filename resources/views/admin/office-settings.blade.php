<x-app-layout>
  <div class="py-6">
    <div class="mx-auto max-w-4xl space-y-6 px-4 sm:px-6 lg:px-8">
      <div class="flex items-center gap-3">
        <div
          class="flex h-11 w-11 shrink-0 items-center justify-center rounded-2xl bg-indigo-100 text-indigo-600 dark:bg-indigo-500/20 dark:text-indigo-400">
          <x-heroicon-o-building-office-2 class="h-6 w-6" />
        </div>
        <div>
          <h1 class="text-lg font-bold text-gray-900 dark:text-white">Informasi Kantor & Kop Laporan</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400">Kelola identitas instansi, logo, alamat resmi, dan penandatangan dokumen cetak laporan absensi.</p>
        </div>
      </div>

      <livewire:admin.office-settings-component />
    </div>
  </div>
</x-app-layout>
