<x-app-layout>
  <div class="mx-auto w-full max-w-7xl px-4 pb-6 pt-6 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
      <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
        <x-heroicon-o-clipboard-document-list class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
      </div>
      <div>
        <h1 class="whitespace-nowrap text-2xl font-bold text-gray-900 dark:text-white">Pengajuan Izin</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola pengajuan izin dan sakit karyawan</p>
      </div>
    </div>

    <div class="mt-6">
      @livewire('admin.leave-request-component')
    </div>
  </div>
</x-app-layout>