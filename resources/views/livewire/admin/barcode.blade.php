<div>
  <script src="{{ url('/assets/js/qrcode.min.js') }}"></script>

  {{-- Header + Aksi --}}
  <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
    <p class="text-sm text-gray-500 dark:text-gray-400">
      {{ $barcodes->count() }} titik barcode tersedia
    </p>
    <div class="flex flex-col gap-2 sm:flex-row">
      <x-secondary-button href="{{ route('admin.barcodes.downloadall') }}" class="w-full justify-center sm:w-auto">
        <x-heroicon-o-arrow-down-tray class="mr-2 h-4 w-4" />
        Download Semua
      </x-secondary-button>
      <x-button href="{{ route('admin.barcodes.create') }}" class="w-full justify-center sm:w-auto">
        <x-heroicon-o-plus class="mr-2 h-4 w-4" />
        Buat Barcode Baru
      </x-button>
    </div>
  </div>

  {{-- Grid Kartu --}}
  <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
    @forelse ($barcodes as $barcode)
      <div
        class="flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm ring-1 ring-gray-100 transition hover:shadow-md dark:bg-gray-800 dark:ring-gray-700">
        <div class="flex items-center justify-between border-b border-gray-100 px-5 py-3 dark:border-gray-700">
          <a href="{{ route('admin.barcodes.show', $barcode->id) }}"
            class="truncate text-sm font-bold text-gray-900 transition hover:text-indigo-600 dark:text-white dark:hover:text-indigo-400">
            {{ $barcode->name }}
          </a>
          <span
            class="shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-300">
            {{ $barcode->radius }} m
          </span>
        </div>

        <a href="{{ route('admin.barcodes.show', $barcode->id) }}" class="flex items-center justify-center p-5">
          <div id="qrcode{{ $barcode->id }}" class="h-48 w-48 bg-transparent"></div>
        </a>

        <div class="grid grid-cols-1 gap-2 border-t border-gray-100 px-5 py-4 sm:grid-cols-3 dark:border-gray-700">
          <x-secondary-button href="{{ route('admin.barcodes.download', $barcode->id) }}" class="w-full justify-center">
            <x-heroicon-o-arrow-down-tray class="mr-1.5 h-4 w-4" />
            Download
          </x-secondary-button>
          <x-button href="{{ route('admin.barcodes.edit', $barcode->id) }}" class="w-full justify-center">
            <x-heroicon-o-pencil-square class="mr-1.5 h-4 w-4" />
            Edit
          </x-button>
          <x-danger-button wire:click="confirmDeletion({{ $barcode->id }}, '{{ $barcode->name }}')" class="w-full justify-center">
            <x-heroicon-o-trash class="h-4 w-4" />
          </x-danger-button>
        </div>

        <div class="space-y-1.5 px-5 pb-5">
          <a href="https://www.google.com/maps/search/?api=1&query={{ $barcode->latitude }},{{ $barcode->longitude }}"
            target="_blank"
            class="flex items-center gap-2 text-xs text-gray-500 transition hover:text-blue-500 hover:underline dark:text-gray-400">
            <x-heroicon-o-map-pin class="h-4 w-4 shrink-0" />
            <span class="truncate">{{ $barcode->latitude . ', ' . $barcode->longitude }}</span>
          </a>
          <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
            <x-heroicon-o-circle-stack class="h-4 w-4 shrink-0" />
            <span class="truncate">{{ $barcode->value }}</span>
          </div>
        </div>
      </div>
    @empty
      <div
        class="col-span-full flex flex-col items-center justify-center rounded-3xl bg-white px-6 py-14 text-center shadow-sm dark:bg-gray-800">
        <div class="flex h-14 w-14 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-700">
          <x-heroicon-o-qr-code class="h-7 w-7 text-gray-400 dark:text-gray-500" />
        </div>
        <p class="mt-4 text-sm font-semibold text-gray-700 dark:text-gray-200">Belum ada barcode</p>
        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Buat barcode pertama kamu untuk mulai absensi.</p>
        <a href="{{ route('admin.barcodes.create') }}"
          class="mt-4 rounded-full bg-indigo-600 px-5 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
          Buat Barcode Baru
        </a>
      </div>
    @endforelse
  </div>

  <x-confirmation-modal wire:model="confirmingDeletion">
    <x-slot name="title">
      Hapus Barcode
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

  @script
    <script type="text/javascript">
      let barcodes = @json(
        $barcodes->map(fn($barcode) => [
            'id' => $barcode->id,
            'value' => $barcode->value
        ])
      );

      let isDark = $store.darkMode.on;

      barcodes.forEach(({ id, value }) => {
        new QRCode(document.getElementById("qrcode" + id), {
          text: value,
          width: 192,
          height: 192,
          colorDark: $store.darkMode.on ? "#ffffff" : "#000000",
          colorLight: $store.darkMode.on ? "#000000" : "#ffffff",
          correctLevel: QRCode.CorrectLevel.M
        });
      });
      setInterval(() => {
        if (isDark == $store.darkMode.on &&
          document.getElementById("qrcode" + barcodes[0]['id']).hasAttribute("title")) {
          return;
        }
        isDark = $store.darkMode.on;
        barcodes.forEach(({ id, value }) => {
          if (!document.getElementById("qrcode" + id)) {
            return;
          }
          document.getElementById("qrcode" + id).innerHTML = "";
          new QRCode(document.getElementById("qrcode" + id), {
            text: value,
            width: 192,
            height: 192,
            colorDark: $store.darkMode.on ? "#ffffff" : "#000000",
            colorLight: $store.darkMode.on ? "#000000" : "#ffffff",
            correctLevel: QRCode.CorrectLevel.M,
          });
        });
      }, 250);
    </script>
  @endscript
</div>