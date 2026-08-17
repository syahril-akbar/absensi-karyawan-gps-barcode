@props(['compact' => false])

@if ($compact)
  <div x-data="installPrompt" x-cloak x-show="canInstall" {{ $attributes }}>
    <button type="button" @click="install()"
      class="inline-flex items-center justify-center rounded-full p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-300"
      title="Install Aplikasi">
      <x-heroicon-o-arrow-down-tray class="h-5 w-5" />
    </button>
  </div>
@else
  <div x-data="installPrompt" x-cloak x-show="show" {{ $attributes }}
    class="flex flex-col gap-3 rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
    <div class="flex items-center gap-3">
      <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
        <x-heroicon-o-arrow-down-tray class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
      </div>
      <div class="min-w-0 flex-1">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Install Aplikasi</h3>
        <p class="text-xs text-gray-500 dark:text-gray-400">Akses cepat dari layar utama, tanpa APK</p>
      </div>
      <button type="button" @click="iosHelp = !iosHelp" x-show="ios"
        class="shrink-0 rounded-full bg-gray-100 px-3 py-1.5 text-xs font-semibold text-gray-600 transition hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
        Cara Install
      </button>
    </div>

    <button type="button" x-show="canInstall" @click="install()"
      class="inline-flex w-full items-center justify-center rounded-full bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
      <x-heroicon-o-arrow-down-tray class="mr-2 h-4 w-4" />
      Install Sekarang
    </button>

    <div x-show="iosHelp && ios" x-cloak
      class="rounded-2xl bg-gray-50 p-4 text-xs leading-relaxed text-gray-600 dark:bg-gray-700 dark:text-gray-300">
      <p class="font-semibold text-gray-800 dark:text-gray-200">Cara install di iPhone/iPad:</p>
      <ol class="mt-1 list-decimal space-y-0.5 pl-4">
        <li>Buka aplikasi ini lewat Safari</li>
        <li>Ketuk ikon Bagikan (kotak dengan panah ke atas)</li>
        <li>Pilih <b>Tambah ke Layar Utama</b></li>
        <li>Ketuk <b>Tambah</b> di kanan atas</li>
      </ol>
    </div>
  </div>
@endif