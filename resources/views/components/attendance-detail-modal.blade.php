<x-modal wire:model="showDetail" onclose="removeMap()">
  <div class="px-6 py-4">
    @if ($currentAttendance)
      @php
        $isExcused = $currentAttendance['status'] == 'excused' || $currentAttendance['status'] == 'sick';
        $showMap = $currentAttendance['latitude'] && $currentAttendance['longitude'] && !$isExcused;
        $statusPill = [
            'present' => 'bg-green-100 text-green-700 dark:bg-green-900/60 dark:text-green-200',
            'late' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/60 dark:text-amber-200',
            'excused' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/60 dark:text-blue-200',
            'sick' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/60 dark:text-purple-200',
            'incomplete' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/60 dark:text-orange-200',
            'absent' => 'bg-red-100 text-red-700 dark:bg-red-900/60 dark:text-red-200',
        ][$currentAttendance['status']] ?? 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200';
      @endphp

      <div class="flex items-center justify-between gap-3">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $currentAttendance['name'] }}</h3>
        <span
          class="shrink-0 rounded-full px-3 py-1 text-xs font-semibold {{ $statusPill }}">
          {{ __('status_' . $currentAttendance['status']) }}
        </span>
      </div>

      <div class="mt-5 space-y-4">
        <div>
          <x-label for="nip" value="{{ __('NIP') }}" />
          <x-input type="text" class="mt-1 w-full" id="nip" disabled value="{{ $currentAttendance['nip'] }}"></x-input>
        </div>

        <div class="flex w-full gap-3">
          <div class="w-full">
            <x-label for="date" value="{{ __('Date') }}" />
            <x-input type="text" class="mt-1 w-full" id="date" disabled
              value="{{ $currentAttendance['date'] }}"></x-input>
          </div>
          <div class="w-full">
            <x-label for="status" value="{{ __('Status') }}" />
            <x-input type="text" class="mt-1 w-full" id="status" disabled
              value="{{ __('status_' . $currentAttendance['status']) }}"></x-input>
          </div>
        </div>

        @if ($isExcused)
          <div>
            <x-label for="address" value="{{ __('Address') }}" />
            <x-input type="text" class="mt-1 w-full" id="address" disabled
              value="{{ $currentAttendance['address'] }}" />
          </div>
        @endif

        @if ($currentAttendance['attachment'])
          <div>
            <x-label for="attachment" value="{{ __('Attachment') }}" />
            <img src="{{ $currentAttendance['attachment'] }}" alt="Attachment"
              class="mt-2 max-h-48 object-contain sm:max-h-64 md:max-h-72">
          </div>
        @endif

        @if ($currentAttendance['note'])
          <div>
            <x-label for="note" value="Keterangan" />
            <x-textarea type="text" id="note" disabled value="{{ $currentAttendance['note'] }}"
              class="mt-1" />
          </div>
        @endif

        @if ($showMap)
          <div>
            <x-label for="map" value="Koordinat Lokasi Absen" />
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-300">
              {{ $currentAttendance['latitude'] }}, {{ $currentAttendance['longitude'] }}
            </p>
            <div class="isolate mt-2 h-52 w-full rounded-2xl md:h-64" id="map"></div>
          </div>
        @endif

        @if ($currentAttendance['time_in'] || $currentAttendance['time_out'])
          <div class="grid grid-cols-2 gap-3">
            <div>
              <x-label for="time_in" value="Waktu Masuk" />
              <x-input type="text" class="mt-1 w-full" id="time_in" disabled
                value="{{ $currentAttendance['time_in'] ?? '-' }}"></x-input>
            </div>
            <div>
              <x-label for="time_out" value="Waktu Keluar" />
              <x-input type="text" class="mt-1 w-full" id="time_out" disabled
                value="{{ $currentAttendance['time_out'] ?? '-' }}"></x-input>
            </div>
          </div>
        @endif

        @if (($currentAttendance['shift'] ?? false) || ($currentAttendance['barcode'] ?? false))
          <div class="grid grid-cols-2 gap-3">
            @if ($currentAttendance['shift'] ?? false)
              <div>
                <x-label for="shift" value="Shift" />
                <x-input class="mt-1 w-full" type="text" id="shift" disabled
                  value="{{ $currentAttendance['shift']['name'] }}"></x-input>
              </div>
            @endif
            @if ($currentAttendance['barcode'] ?? false)
              <div>
                <x-label for="barcode" value="Barcode" />
                <x-input class="mt-1 w-full" type="text" id="barcode" disabled
                  value="{{ $currentAttendance['barcode']['name'] }}"></x-input>
              </div>
            @endif
          </div>
        @endif
      </div>
    @endif
  </div>
</x-modal>

@push('attendance-detail-scripts')
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
  <script>
    let map = null;

    function setLocation(lat, lng) {
      removeMap();
      setTimeout(() => {
        map = L.map('map').setView([Number(lat), Number(lng)], 19);
        L.marker([Number(lat), Number(lng)]).addTo(map);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 21,
        }).addTo(map);
      }, 500);
    }

    function removeMap() {
      if (map !== null) map.remove();
      map = null;
    }
  </script>
@endpush