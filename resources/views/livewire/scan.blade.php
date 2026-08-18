<div class="mt-6 flex w-full flex-col gap-5">
  @php
    use Illuminate\Support\Carbon;
  @endphp
  @pushOnce('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
      #success-popup {
        z-index: 99;
      }
      .check-circle,
      .check-mark {
        transform-origin: center;
      }
      #success-check.animate .check-circle {
        stroke-dasharray: 166;
        stroke-dashoffset: 166;
        animation: popup-circle 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;
      }
      #success-check.animate .check-mark {
        stroke-dasharray: 48;
        stroke-dashoffset: 48;
        animation: popup-check 0.35s cubic-bezier(0.65, 0, 0.45, 1) 0.55s forwards;
      }
      @keyframes popup-circle {
        0% { stroke-dashoffset: 166; transform: scale(0.9); }
        100% { stroke-dashoffset: 0; transform: scale(1); }
      }
      @keyframes popup-check {
        0% { stroke-dashoffset: 48; }
        100% { stroke-dashoffset: 0; }
      }
    </style>
  @endpushOnce
  @pushOnce('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
      integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
      let currentMap = document.getElementById('currentMap');
      let map = document.getElementById('map');

      setTimeout(() => {
        toggleMap();
        toggleCurrentMap();
      }, 1000);

      function toggleCurrentMap() {
        const mapIsVisible = currentMap.style.display === "none";
        currentMap.style.display = mapIsVisible ? "block" : "none";
        document.querySelector('#toggleCurrentMap').innerHTML = mapIsVisible ?
          `<x-heroicon-s-chevron-up class="mr-2 h-5 w-5" />` :
          `<x-heroicon-s-chevron-down class="mr-2 h-5 w-5" />`;
      }

      function toggleMap() {
        const mapIsVisible = map.style.display === "none";
        map.style.display = mapIsVisible ? "block" : "none";
      }
    </script>
  @endpushOnce

  @if (!$isAbsence && !$isHolidayToday)
    <script src="{{ url('/assets/js/html5-qrcode.min.js') }}"></script>
  @endif

  <!-- Status Card: Absen Masuk / Keluar -->
  <div class="relative overflow-hidden rounded-3xl bg-indigo-600 p-6 text-white shadow-lg shadow-indigo-600/30 dark:shadow-indigo-900/40">
    <div class="pointer-events-none absolute -right-10 -top-10 h-40 w-40 rounded-full bg-white/10"></div>
    <div class="pointer-events-none absolute -bottom-16 -left-10 h-44 w-44 rounded-full bg-white/10"></div>

    <div class="relative flex items-center justify-between">
      <div>
        <p class="text-xs font-medium uppercase tracking-wider text-indigo-200">{{ $isHolidayToday ? 'Hari Ini Libur' : 'Status Hari Ini' }}</p>
        <h2 class="mt-1 text-xl font-bold">
          @if ($isHolidayToday)
            {{ __('Libur') }}@if ($holidayName) · {{ $holidayName }}@endif
          @elseif ($attendance)
            {{ __('status_' . $attendance->status) }}
          @else
            {{ __('Belum Absen') }}
          @endif
        </h2>
        @if ($isHolidayToday)
          <p class="mt-1 text-xs font-semibold text-indigo-200">Absensi otomatis nonaktif hari ini</p>
        @endif
      </div>
      <div
        class="rounded-full bg-white/20 px-3 py-1 text-xs font-semibold backdrop-blur">
        {{ now()->format('H:i') }}
      </div>
    </div>

    @if ($attendance && is_null($attendance->time_out) && in_array($attendance->status, ['present', 'late', 'incomplete']))
      {{-- Kalau sudah masuk & belum keluar: pilih mode keluar secara eksplisit --}}
      <div class="mt-4">
        @if ($isCheckoutMode)
          <div class="rounded-2xl bg-white/15 p-3 backdrop-blur">
            <p class="flex items-center gap-2 text-sm font-bold">
              <x-heroicon-s-arrow-path class="h-4 w-4 animate-spin" />
              Mode Absen Keluar Aktif
            </p>
            <p class="mt-1 text-xs text-indigo-100">Scan barcode QR untuk absen keluar.</p>
            <button wire:click="cancelCheckoutMode"
              class="mt-2 text-xs font-semibold text-indigo-200 underline hover:text-white">
              Batal
            </button>
          </div>
        @else
          <button
            onclick="askCheckoutMode()"
            class="flex items-center gap-2 rounded-2xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-emerald-500/30 transition hover:bg-emerald-600 active:scale-[0.98]">
            <x-heroicon-o-arrow-left-end-on-rectangle class="h-4 w-4" />
            Absen Keluar
          </button>
          <p class="mt-1 text-xs text-indigo-200">Tekan tombol ini untuk mengaktifkan scan absen keluar.</p>
        @endif
      </div>
    @endif

    <div class="relative mt-6 grid grid-cols-2 gap-4">
      <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
        <div class="flex items-center gap-2 text-indigo-100">
          <x-heroicon-o-arrow-down-tray class="h-5 w-5" />
          <span class="text-xs font-medium uppercase tracking-wider">Absen Masuk</span>
        </div>
        <p class="mt-2 text-2xl font-bold">
          @if ($isAbsence)
            {{ $attendance ? __("status_" . $attendance->status) : '-' }}
          @else
            {{ $attendance?->time_in ? Carbon::parse($attendance?->time_in)->format('H:i') : '--:--' }}
          @endif
        </p>
        @if (!$isAbsence && $attendance?->status == 'late')
          <p class="mt-1 text-xs font-semibold text-amber-300">Terlambat</p>
        @endif
      </div>
      <div class="rounded-2xl bg-white/15 p-4 backdrop-blur">
        <div class="flex items-center gap-2 text-indigo-100">
          <x-heroicon-o-arrow-up-tray class="h-5 w-5" />
          <span class="text-xs font-medium uppercase tracking-wider">Absen Keluar</span>
        </div>
        <p class="mt-2 text-2xl font-bold">
          @if ($isAbsence)
            {{ $attendance ? __("status_" . $attendance->status) : '-' }}
          @else
            {{ $attendance?->time_out ? Carbon::parse($attendance?->time_out)->format('H:i') : '--:--' }}
          @endif
        </p>
      </div>
    </div>
  </div>

  <!-- Messages -->
  <h4 id="scanner-error" class="text-sm font-semibold text-red-500 dark:text-red-400" wire:ignore></h4>

  <!-- Success Popup -->
  <div id="success-popup" class="fixed inset-0 z-50 hidden items-center justify-center px-6"
    wire:ignore aria-hidden="true">
    <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity"
      onclick="closeSuccessPopup()"></div>

    <div id="success-card"
      class="relative w-full max-w-xs scale-95 opacity-0 transition-all duration-300 ease-out">
      <div class="overflow-hidden rounded-3xl bg-white shadow-2xl shadow-slate-900/20 dark:bg-slate-800">
        <!-- Banner -->
        <div id="success-banner"
          class="relative flex flex-col items-center overflow-hidden px-6 pb-7 pt-9 text-white">
          <div class="pointer-events-none absolute -right-10 -top-10 h-32 w-32 rounded-full bg-white/10"></div>
          <div class="pointer-events-none absolute -bottom-12 -left-10 h-32 w-32 rounded-full bg-white/10"></div>

          <div class="relative flex h-20 w-20 items-center justify-center rounded-full bg-white/25 ring-4 ring-white/30">
            <svg id="success-check" viewBox="0 0 52 52" class="h-12 w-12">
              <circle class="check-circle" cx="26" cy="26" r="24" fill="none" stroke="currentColor"
                stroke-width="3" />
              <path class="check-mark" fill="none" stroke="currentColor" stroke-width="4" stroke-linecap="round"
                stroke-linejoin="round" d="M15 27 l7 7 l16 -17" />
            </svg>
          </div>

          <h3 id="success-title" class="relative mt-4 text-lg font-bold">Absen Berhasil</h3>
          <p id="success-subtitle" class="relative mt-1 text-sm text-white/85">Terima kasih</p>
          <span id="success-badge"
            class="relative mt-3 hidden rounded-full bg-white/25 px-3 py-1 text-xs font-semibold"></span>
        </div>

        <!-- Detail -->
        <div class="bg-white px-6 py-5 dark:bg-slate-800">
          <div
            class="flex items-center justify-center gap-3 rounded-2xl bg-slate-50 py-3 dark:bg-slate-700/50">
            <x-heroicon-o-clock class="h-5 w-5 text-slate-400 dark:text-slate-300" />
            <span id="success-time" class="text-2xl font-bold tracking-tight text-slate-800 dark:text-white">--:--</span>
          </div>
          <button onclick="closeSuccessPopup()"
            class="mt-4 w-full rounded-2xl bg-indigo-600 py-3 text-sm font-semibold text-white shadow-lg shadow-indigo-600/30 transition hover:bg-indigo-700 active:scale-[0.98]">
            Selesai
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Scan Card -->
  @if (!$isAbsence && !$isHolidayToday)
    <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
      <div class="flex items-center justify-between">
        <h3 class="text-sm font-bold text-gray-900 dark:text-white">Scan Barcode Absen</h3>
        <span class="flex h-2 w-2">
          <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400">
            <span class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-75"></span>
          </span>
        </span>
      </div>

      <div class="mt-4">
        <x-label for="shift" value="{{ __('Shift') }}" />
        <x-select id="shift" class="mt-1 block w-full" wire:model="shift_id" disabled="{{ !is_null($attendance) }}">
          <option value="">{{ __('Select Shift') }}</option>
          @foreach ($shifts as $shift)
            <option value="{{ $shift->id }}" {{ $shift->id == $shift_id ? 'selected' : '' }}>
              {{ $shift->name . ' | ' . $shift->start_time . ' - ' . $shift->end_time }}
            </option>
          @endforeach
        </x-select>
        @error('shift_id')
          <x-input-error for="shift" class="mt-2" message={{ $message }} />
        @enderror
        @if (is_null($attendance) && $shift_id)
          <p class="mt-2 flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
            <x-heroicon-s-sparkles class="h-3.5 w-3.5" />
            Shift dipilih otomatis sesuai hari ini.
          </p>
        @endif
      </div>

      <div class="mt-4 flex justify-center rounded-2xl outline outline-gray-100 dark:outline-slate-700" wire:ignore>
        <div id="scanner" class="w-72 min-h-72 rounded-xl outline-dashed outline-slate-400 dark:outline-slate-500 sm:w-80"></div>
      </div>
    </div>
  @endif

  <!-- Location Card -->
  <div class="rounded-3xl bg-white p-5 shadow-sm dark:bg-gray-800">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div class="flex h-9 w-9 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
          <x-heroicon-o-map-pin class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
        </div>
        <div>
          <h3 class="text-sm font-bold text-gray-900 dark:text-white">Lokasi Kamu</h3>
          <p class="text-xs text-gray-500 dark:text-gray-400">Pilih titik absen untuk verifikasi</p>
        </div>
      </div>
      <button class="h-6 text-gray-400" onclick="toggleCurrentMap()" id="toggleCurrentMap">
        <x-heroicon-s-chevron-down class="h-5 w-5" />
      </button>
    </div>

    <div id="latlng" class="mt-3 text-xs text-gray-500 dark:text-gray-300">
      @if (!is_null($currentLiveCoords))
        <a href="{{ \App\Helpers::getGoogleMapsUrl($currentLiveCoords[0], $currentLiveCoords[1]) }}" target="_blank"
          class="underline hover:text-blue-400">
          {{ $currentLiveCoords[0] . ', ' . $currentLiveCoords[1] }}
        </a>
      @else
        {{ __('Your location') . ': -, -' }}
      @endif
      <div class="my-4 isolate h-56 w-full rounded-2xl md:h-64" id="currentMap" wire:ignore></div>
    </div>
  </div>

  <!-- Coordinate Attendance & Map -->
  @if (!$isHolidayToday)
  <button
    class="flex items-center justify-between rounded-3xl bg-indigo-500 p-5 text-left text-white shadow-md shadow-indigo-500/30 transition hover:bg-indigo-600 dark:shadow-indigo-900/40"
    {{ is_null($attendance?->lat_lng) ? 'disabled' : 'onclick=toggleMap()' }} id="toggleMap">
    <div>
      <h4 class="text-sm font-bold">Koordinat Absen</h4>
      <p class="mt-1 text-xs text-purple-100">
        @if (is_null($attendance?->lat_lng))
          Belum Absen
        @else
          <a href="{{ \App\Helpers::getGoogleMapsUrl($attendance?->latitude, $attendance?->longitude) }}" target="_blank"
            class="underline hover:text-white">
            {{ $attendance?->latitude . ', ' . $attendance?->longitude }}
          </a>
        @endif
      </p>
    </div>
    <x-heroicon-o-map-pin class="h-6 w-6" />
  </button>

  <div class="my-1 isolate h-56 w-full rounded-2xl md:h-64" id="map" wire:ignore></div>
  @endif

  <hr class="border-gray-200 dark:border-gray-700">

  <!-- Quick Actions -->
  <div class="grid grid-cols-3 gap-3">
    <a href="{{ route('apply-leave') }}" class="group flex flex-col items-center gap-2 rounded-2xl bg-white p-4 shadow-sm transition hover:bg-indigo-50 dark:bg-gray-800 dark:hover:bg-gray-700">
      <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 transition group-hover:scale-105 dark:bg-indigo-900">
        <x-heroicon-o-envelope-open class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
      </div>
      <span class="text-center text-xs font-semibold text-gray-700 dark:text-gray-200">Ajukan Izin</span>
    </a>
    <a href="{{ route('attendance-history') }}" class="group flex flex-col items-center gap-2 rounded-2xl bg-white p-4 shadow-sm transition hover:bg-indigo-50 dark:bg-gray-800 dark:hover:bg-gray-700">
      <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 transition group-hover:scale-105 dark:bg-indigo-900">
        <x-heroicon-o-clock class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
      </div>
      <span class="text-center text-xs font-semibold text-gray-700 dark:text-gray-200">Riwayat Absen</span>
    </a>
    <a href="{{ route('leave-history') }}" class="group flex flex-col items-center gap-2 rounded-2xl bg-white p-4 shadow-sm transition hover:bg-indigo-50 dark:bg-gray-800 dark:hover:bg-gray-700">
      <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 transition group-hover:scale-105 dark:bg-indigo-900">
        <x-heroicon-o-document-text class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
      </div>
      <span class="text-center text-xs font-semibold text-gray-700 dark:text-gray-200">Riwayat Izin</span>
    </a>
  </div>
</div>

@script
  <script>
    const isHolidayToday = @json($isHolidayToday);
    const errorMsg = document.querySelector('#scanner-error');
    getLocation();

    // ---- Success popup ----
    const popup = document.querySelector('#success-popup');
    const popupCard = document.querySelector('#success-card');
    const banner = document.querySelector('#success-banner');
    const checkSvg = document.querySelector('#success-check');
    const titleEl = document.querySelector('#success-title');
    const subtitleEl = document.querySelector('#success-subtitle');
    const badgeEl = document.querySelector('#success-badge');
    const timeEl = document.querySelector('#success-time');

    const popupStyles = {
      in: {
        banner: 'bg-emerald-500',
        title: 'Absen Masuk Berhasil',
        subtitle: 'Selamat bekerja!',
        badge: 'Masuk',
      },
      out: {
        banner: 'bg-indigo-600',
        title: 'Absen Keluar Berhasil',
        subtitle: 'Sampai jumpa, hati-hati di jalan!',
        badge: 'Keluar',
      },
    };

    function showSuccessPopup(result) {
      const type = result?.type === 'out' ? 'out' : 'in';
      const cfg = popupStyles[type];

      banner.className =
        'relative flex flex-col items-center overflow-hidden px-6 pb-7 pt-9 text-white ' +
        cfg.banner;
      titleEl.textContent = cfg.title;
      subtitleEl.textContent = cfg.subtitle;
      timeEl.textContent = result?.time ? result.time.slice(0, 5) : '--:--';

      if (result?.status === 'late') {
        badgeEl.textContent = type === 'out' ? 'Keluar · Telat' : 'Masuk · Terlambat';
        badgeEl.classList.remove('hidden');
      } else {
        badgeEl.classList.add('hidden');
      }

      // restart check animation
      checkSvg.classList.remove('animate');
      void checkSvg.offsetWidth;
      checkSvg.classList.add('animate');

      popup.classList.remove('hidden');
      popup.classList.add('flex');
      popup.setAttribute('aria-hidden', 'false');
      requestAnimationFrame(() => {
        popupCard.classList.remove('scale-95', 'opacity-0');
        popupCard.classList.add('scale-100', 'opacity-100');
      });
    }

    function closeSuccessPopup() {
      popupCard.classList.remove('scale-100', 'opacity-100');
      popupCard.classList.add('scale-95', 'opacity-0');
      setTimeout(() => {
        popup.classList.add('hidden');
        popup.classList.remove('flex');
        popup.setAttribute('aria-hidden', 'true');
      }, 250);
    }

    async function getLocation() {
      if (navigator.geolocation) {
        const map = L.map('currentMap');
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
          maxZoom: 21,
        }).addTo(map);
        navigator.geolocation.watchPosition((position) => {
          console.log(position);
          $wire.$set('currentLiveCoords', [position.coords.latitude, position.coords.longitude]);
          map.setView([
            Number(position.coords.latitude),
            Number(position.coords.longitude),
          ], 13);
          L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        }, (err) => {
          console.error(`ERROR(${err.code}): ${err.message}`);
          alert('{{ __('Please enable your location') }}');
        });
      } else {
        document.querySelector('#scanner-error').innerHTML = "Gagal mendeteksi lokasi";
      }
    }

    if (!$wire.isAbsence && !isHolidayToday) {
      const scanner = new Html5Qrcode('scanner');

      const config = {
        formatsToSupport: [Html5QrcodeSupportedFormats.QR_CODE],
        fps: 15,
        aspectRatio: 1,
        qrbox: {
          width: 240,
          height: 240
        },
        supportedScanTypes: [Html5QrcodeScanType.SCAN_TYPE_CAMERA]
      };

      async function startScanning() {
        if (scanner.getState() === Html5QrcodeScannerState.PAUSED) {
          return scanner.resume();
        }
        await scanner.start({
            facingMode: "environment"
          },
          config,
          onScanSuccess,
        );
      }

      async function onScanSuccess(decodedText, decodedResult) {
        console.log(`Code matched = ${decodedText}`, decodedResult);

        if (scanner.getState() === Html5QrcodeScannerState.SCANNING) {
          scanner.pause(true);
        }

        if (!(await checkTime())) {
          await startScanning();
          return;
        }

        const result = await $wire.scan(decodedText);

        if (result === true) {
          return onAttendanceSuccess();
        } else if (typeof result === 'string') {
          errorMsg.innerHTML = result;
        }

        setTimeout(async () => {
          await startScanning();
        }, 500);
      }

      async function checkTime() {
        const attendance = await $wire.getAttendance();

        if (attendance) {
          // attendance.time_in adalah string "HH:mm:ss". Buat Date hari ini + waktu itu.
          const [h, m, s] = (attendance.time_in || '').split(':').map(Number);
          if (isNaN(h) || isNaN(m) || isNaN(s)) {
            return true; // invalid time, skip check
          }
          const today = new Date();
          const timeInDate = new Date(today.getFullYear(), today.getMonth(), today.getDate(), h, m, s);
          const diffHours = (Date.now() - timeInDate.getTime()) / (1000 * 3600);
          const minAttendanceTime = 1; // 1 jam minimal sebelum bisa checkout

          console.log(`checkTime diff = ${diffHours}h`);
          if (diffHours <= minAttendanceTime) {
            const timeInStr = timeInDate.toLocaleTimeString([], {
              hour: 'numeric',
              minute: 'numeric',
              second: 'numeric',
              hour12: false,
            });
            const confirmation = confirm(
              `Anda baru saja absen masuk pada ${timeInStr}. Minimal ${minAttendanceTime} jam sebelum bisa absen keluar. Yakin ingin lanjut?`
            );
            return confirmation;
          }
        }
        return true;
      }

      function askCheckoutMode() {
        // Panggil Livewire untuk enable checkout mode
        $wire.enterCheckoutMode().then((allowed) => {
          if (allowed) {
            // Livewire render ulang, scanner perlu restart
            // (onScanSuccess akan dipanggil saat scan berhasil, mode checkout aktif)
          } else {
            errorMsg.innerHTML = 'Tidak bisa masuk mode keluar. Cek status absensi.';
          }
        });
      }

      // Listen untuk perubahan isCheckoutMode dari Livewire
      document.addEventListener('livewire:load', () => {
        Livewire.on('reset-error', () => {
          errorMsg.innerHTML = '';
        });
      });

      function onAttendanceSuccess() {
        scanner.stop();
        errorMsg.innerHTML = '';
        const scanResult = {{ json($scanResult) }};
        showSuccessPopup(scanResult);
      }

      const observer = new MutationObserver((mutationList, observer) => {
        const classes = ['text-white', 'bg-blue-500', 'dark:bg-blue-400', 'rounded-md', 'px-3', 'py-1'];
        for (const mutation of mutationList) {
          if (mutation.type === 'childList') {
            const startBtn = document.querySelector('#html5-qrcode-button-camera-start');
            const stopBtn = document.querySelector('#html5-qrcode-button-camera-stop');
            const fileBtn = document.querySelector('#html5-qrcode-button-file-selection');
            const permissionBtn = document.querySelector('#html5-qrcode-button-camera-permission');

            if (startBtn) {
              startBtn.classList.add(...classes);
              stopBtn.classList.add(...classes, 'bg-red-500');
              fileBtn.classList.add(...classes);
            }

            if (permissionBtn)
              permissionBtn.classList.add(...classes);
          }
        }
      });

      observer.observe(document.querySelector('#scanner'), {
        childList: true,
        subtree: true,
      });

      const shift = document.querySelector('#shift');
      const msg = 'Pilih shift terlebih dahulu';
      let isRendered = false;
      setTimeout(() => {
        if (!shift.value) {
          errorMsg.innerHTML = msg;
        } else {
          startScanning();
          isRendered = true;
        }
      }, 1000);
      shift.addEventListener('change', () => {
        if (!isRendered) {
          startScanning();
          isRendered = true;
          errorMsg.innerHTML = '';
        }
        if (!shift.value) {
          scanner.pause(true);
          errorMsg.innerHTML = msg;
        } else if (scanner.getState() === Html5QrcodeScannerState.PAUSED) {
          scanner.resume();
          errorMsg.innerHTML = '';
        }
      });

      const map = L.map('map').setView([
        Number({{ $attendance?->latitude }}),
        Number({{ $attendance?->longitude }}),
      ], 13);
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 21,
      }).addTo(map);
      L.marker([
        Number({{ $attendance?->latitude }}),
        Number({{ $attendance?->longitude }}),
      ]).addTo(map);
    }
  </script>
@endscript