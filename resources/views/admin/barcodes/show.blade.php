<x-app-layout>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce

  <div class="mx-auto w-full max-w-4xl px-4 pb-6 pt-6 sm:px-6 lg:px-8">
    <div class="flex items-center gap-3">
      <a href="{{ route('admin.barcodes') }}"
        class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
        <x-heroicon-o-chevron-left class="h-5 w-5" />
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Detail Barcode</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Informasi titik absen barcode</p>
      </div>
    </div>

    <div class="mt-6 grid grid-cols-1 gap-5 lg:grid-cols-2">
      {{-- QR --}}
      <div class="flex flex-col items-center rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
        <div class="flex items-center justify-between self-stretch">
          <h3 class="truncate text-base font-bold text-gray-900 dark:text-white">{{ $barcode->name }}</h3>
          <span
            class="ml-2 shrink-0 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-300">
            {{ $barcode->radius }} m
          </span>
        </div>

        <div id="qrcode" class="mt-5 h-56 w-56 bg-transparent"></div>

        <div class="mt-5 flex w-full flex-col gap-2 sm:flex-row">
          <a href="{{ route('admin.barcodes.download', $barcode->id) }}"
            class="inline-flex w-full items-center justify-center rounded-full bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-indigo-700">
            <x-heroicon-o-arrow-down-tray class="mr-2 h-4 w-4" />
            Download
          </a>
          <a href="{{ route('admin.barcodes.edit', $barcode->id) }}"
            class="inline-flex w-full items-center justify-center rounded-full bg-white px-4 py-2.5 text-sm font-semibold text-indigo-600 ring-1 ring-inset ring-indigo-200 transition hover:bg-indigo-50 dark:bg-gray-800 dark:text-indigo-300 dark:ring-indigo-800 dark:hover:bg-gray-700">
            <x-heroicon-o-pencil-square class="mr-2 h-4 w-4" />
            Edit
          </a>
        </div>
      </div>

      {{-- Info --}}
      <div class="rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
        <h3 class="text-base font-bold text-gray-900 dark:text-white">Informasi Titik Absen</h3>

        <div class="mt-4 space-y-3">
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Value Barcode" />
            <p class="mt-1 break-all text-sm font-semibold text-gray-900 dark:text-white">{{ $barcode->value }}</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Radius Valid" />
            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">{{ $barcode->radius }} meter</p>
          </div>
          <div class="rounded-2xl bg-gray-50 p-4 dark:bg-gray-700">
            <x-label value="Koordinat" />
            <p class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
              {{ $barcode->latitude }}, {{ $barcode->longitude }}
            </p>
            <a href="https://www.google.com/maps/search/?api=1&query={{ $barcode->latitude }},{{ $barcode->longitude }}"
              target="_blank"
              class="mt-1 inline-flex items-center gap-1 text-xs font-medium text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400">
              <x-heroicon-o-map-pin class="h-4 w-4" />
              Buka di Google Maps
            </a>
          </div>
        </div>

        <div class="mt-4">
          <x-label value="Lokasi" />
          <div id="map" class="isolate mt-2 h-56 w-full rounded-2xl"></div>
        </div>
      </div>
    </div>
  </div>

  @pushOnce('scripts')
    <script src="{{ url('/assets/js/qrcode.min.js') }}"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script type="text/javascript">
      let barcodeValue = @json($barcode->value);
      let rendered = false;
      let renderedDark = null;

      function currentDark() {
        return !!(window.Alpine && window.Alpine.store('darkMode') && window.Alpine.store('darkMode').on);
      }

      function renderQr() {
        const el = document.getElementById("qrcode");
        if (!el) return;
        const isDark = currentDark();
        if (rendered && isDark === renderedDark) return;
        rendered = true;
        renderedDark = isDark;
        el.innerHTML = "";
        new QRCode(el, {
          text: barcodeValue,
          width: 224,
          height: 224,
          colorDark: isDark ? "#ffffff" : "#000000",
          colorLight: isDark ? "#000000" : "#ffffff",
          correctLevel: QRCode.CorrectLevel.M
        });
      }

      renderQr();
      setInterval(renderQr, 250);

      window.addEventListener("load", function() {
        const lat = Number(@json($barcode->latitude));
        const lng = Number(@json($barcode->longitude));
        const map = L.map('map').setView([lat, lng], 17);
        L.marker([lat, lng]).addTo(map);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 21,
        }).addTo(map);
      });
    </script>
  @endPushOnce
</x-app-layout>