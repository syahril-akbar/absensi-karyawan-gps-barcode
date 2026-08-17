<x-app-layout>
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
  @endpushOnce

  <div class="mx-auto w-full max-w-3xl px-4 pb-6 pt-6">
    <div class="flex items-center gap-3">
      <a href="{{ route('admin.barcodes') }}"
        class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
        <x-heroicon-o-chevron-left class="h-5 w-5" />
      </a>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Buat Barcode Baru</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Tentukan titik absen dan radius valid</p>
      </div>
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
      <form action="{{ route('admin.barcodes.store') }}" method="post">
        @csrf

        <div class="flex flex-col gap-4 md:flex-row md:items-start md:gap-4">
          <div class="w-full">
            <x-label for="name">Nama Barcode</x-label>
            <x-input name="name" id="name" class="mt-2 block w-full rounded-xl" type="text" :value="old('name')"
              placeholder="cth: Barcode Kantor Utama" />
            @error('name')
              <x-input-error for="name" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
          <div class="w-full">
            <x-label for="value">Value Barcode</x-label>
            @livewire('admin.barcode-value-input-component')
          </div>
        </div>

        <div class="mt-5">
          <x-label for="radius">Radius Valid Absen</x-label>
          <x-input name="radius" id="radius" class="mt-2 block w-full rounded-xl" type="number" :value="old('radius')"
            placeholder="cth: 50 (meter)" />
          @error('radius')
            <x-input-error for="radius" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>

        <div class="mt-6 border-t border-gray-100 pt-5 dark:border-gray-700">
          <h3 class="text-sm font-bold text-gray-900 dark:text-white">Koordinat Lokasi</h3>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Klik peta untuk memilih lokasi titik absen</p>

          <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
            <div class="w-full">
              <x-label for="lat">Latitude</x-label>
              <x-input name="lat" id="lat" class="mt-2 block w-full rounded-xl" type="text" :value="old('lat')"
                placeholder="cth: -6.12345" />
              @error('lat')
                <x-input-error for="lat" class="mt-2" message="{{ $message }}" />
              @enderror
            </div>
            <div class="w-full">
              <x-label for="lng">Longitude</x-label>
              <x-input name="lng" id="lng" class="mt-2 block w-full rounded-xl" type="text" :value="old('lng')"
                placeholder="cth: 6.12345" />
              @error('lng')
                <x-input-error for="lng" class="mt-2" message="{{ $message }}" />
              @enderror
            </div>
          </div>

          <div class="mt-4">
            <x-button type="button" onclick="toggleMap()" class="text-nowrap">
              <x-heroicon-s-map-pin class="mr-2 h-5 w-5" /> Tampilkan/Sembunyikan Peta
            </x-button>
            <div id="map" class="isolate mt-4 h-72 w-full rounded-2xl md:h-96"></div>
          </div>
        </div>

        <div class="mt-6 flex justify-end">
          <x-button class="w-full justify-center sm:w-auto">
            <x-heroicon-o-check class="mr-2 h-4 w-4" />
            {{ __('Save') }}
          </x-button>
        </div>
      </form>
    </div>
  </div>

  @pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
      window.addEventListener("load", function() {
        window.initializeMap({
          onUpdate: (lat, lng) => {
            document.getElementById('lat').value = lat;
            document.getElementById('lng').value = lng;
          },
          location: @if (old('lat') && old('lng'))
            [Number({{ old('lat') }}), Number({{ old('lng') }})]
          @else
            undefined
          @endif
        });
      });

      let map = document.getElementById('map');

      function toggleMap() {
        map.style.display = map.style.display === "none" ? "block" : "none";
      }
    </script>
  @endPushOnce
</x-app-layout>