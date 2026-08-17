<x-app-layout>
  <div class="mx-auto w-full max-w-2xl px-4 pb-6 pt-6">
    <div class="flex items-center justify-between">
      <div class="flex items-center gap-3">
        <a href="{{ url()->previous() }}"
          class="flex h-11 w-11 items-center justify-center rounded-full bg-white text-gray-600 shadow-sm transition hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700">
          <x-heroicon-o-chevron-left class="h-5 w-5" />
        </a>
        <div>
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Ajukan Izin</h1>
          <p class="text-sm text-gray-500 dark:text-gray-400">Isi data pengajuan izin/sakit kamu</p>
        </div>
      </div>
      <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
        <x-heroicon-o-envelope-open class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
      </div>
    </div>

    <div class="mt-6 rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800">
      <form action="{{ route('store-leave-request') }}" method="post" enctype="multipart/form-data">
        @csrf

        <!-- Tipe Pengajuan -->
        <div>
          <x-label for="status" value="{{ __('Status') }}" />
          <div class="mt-2 grid grid-cols-2 gap-3" x-data="{ type: '{{ old('status') ?? $attendance?->status }}' }">
            <label
              class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-4 transition"
              :class="type === 'excused' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/40' : 'border-gray-200 hover:border-gray-300 dark:border-gray-600 dark:hover:border-gray-500'">
              <input type="radio" name="status" value="excused" x-model="type" required class="sr-only">
              <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900">
                <x-heroicon-o-paper-airplane class="h-5 w-5 text-blue-600 dark:text-blue-300" />
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Izin</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Keperluan pribadi</p>
              </div>
            </label>
            <label
              class="flex cursor-pointer items-center gap-3 rounded-2xl border-2 p-4 transition"
              :class="type === 'sick' ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/40' : 'border-gray-200 hover:border-gray-300 dark:border-gray-600 dark:hover:border-gray-500'">
              <input type="radio" name="status" value="sick" x-model="type" class="sr-only">
              <div class="flex h-10 w-10 items-center justify-center rounded-full bg-purple-100 dark:bg-purple-900">
                <x-heroicon-o-heart class="h-5 w-5 text-purple-600 dark:text-purple-300" />
              </div>
              <div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Sakit</p>
                <p class="text-xs text-gray-500 dark:text-gray-400">Alasan kesehatan</p>
              </div>
            </label>
          </div>
          @error('status')
            <x-input-error for="status" class="mt-2" message="{{ $message }}" />
          @enderror
        </div>

        <!-- Tanggal -->
        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
          <div>
            <x-label for="from" value="Tanggal mulai" />
            <x-input type="date" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" id="from"
              class="mt-2 block w-full rounded-xl" name="from" required />
            @error('from')
              <x-input-error for="from" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
          <div>
            <x-label for="to" value="Tanggal berakhir (Opsional)" />
            <x-input type="date" id="to" min="{{ date('Y-m-d') }}" class="mt-2 block w-full rounded-xl"
              name="to" />
            @error('to')
              <x-input-error for="to" class="mt-2" message="{{ $message }}" />
            @enderror
          </div>
        </div>

        <!-- Keterangan -->
        <div class="mt-6">
          <x-label for="note" value="Keterangan" />
          <x-textarea id="note" type="text" rows="3"
            class="mt-2 block w-full rounded-xl" name="note" required
            placeholder="Tulis alasan pengajuan..."
            value="{{ old('note') ?? $attendance?->note }}" />
          <x-input-error for="note" class="mt-2" />
        </div>

        <!-- Attachment -->
        <div class="mt-6" x-data="{ filename: null, preview: null }">
          <input type="file" value="{{ old('attachment') ?? $attendance?->attachment }}" class="hidden"
            id="attachment" name="attachment" x-ref="attachment"
            x-on:change="
                      filename = $refs.attachment.files[0].name;
                      const reader = new FileReader();
                      reader.onload = (e) => {
                          preview = e.target.result;
                      };
                      reader.readAsDataURL($refs.attachment.files[0]);
                  " />

          <x-label for="attachment" value="{{ __('Attachment') }}" />

          <div class="mb-2 mt-2" x-show="preview" style="display: none;">
            <span class="block h-48 w-full rounded-2xl bg-contain bg-left bg-no-repeat"
              x-bind:style="'background-image: url(\'' + preview + '\');'">
            </span>
          </div>

          @if ($attendance?->attachment)
            <div class="mb-2 mt-2" x-show="!preview">
              <img class="block h-48 w-full rounded-2xl object-contain object-left"
                src="{{ $attendance?->attachment_url }}" />
            </div>
          @endif

          <div class="mt-3 flex flex-wrap gap-2">
            <x-secondary-button class="me-0" type="button" x-on:click.prevent="$refs.attachment.click()">
              <x-heroicon-o-paper-clip class="mr-2 h-4 w-4" />
              {{ __('Select Attachment') }}
            </x-secondary-button>
            <x-secondary-button type="button" x-show="preview" x-cloak
              x-on:click="filename = null; preview = null">
              <x-heroicon-o-trash class="mr-2 h-4 w-4" />
              {{ __('Remove Attachment') }}
            </x-secondary-button>
          </div>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Maksimal 3MB. Foto surat izin/dokumen pendukung.</p>

          <x-input-error for="attachment" class="mt-2" />
        </div>

        <input type="hidden" id="lat" name="lat" value="{{ $attendance?->latitude }}">
        <input type="hidden" id="lng" name="lng" value="{{ $attendance?->longitude }}">

        <div class="mt-8 flex items-center justify-end">
          <x-button class="ms-4 w-full justify-center sm:w-auto">
            <x-heroicon-o-check class="mr-2 h-4 w-4" />
            {{ __('Save') }}
          </x-button>
        </div>
      </form>
    </div>
  </div>

  @pushOnce('scripts')
    <script>
      getLocation();

      async function getLocation() {
        if (navigator.geolocation) {
          navigator.geolocation.watchPosition((position) => {
            console.log(position);
            document.getElementById('lat').value = position.coords.latitude;
            document.getElementById('lng').value = position.coords.longitude;
          }, (err) => {
            console.error(`ERROR(${err.code}): ${err.message}`);
            alert('{{ __('Please enable your location') }}');
          });
        }
      }
    </script>
  @endPushOnce
</x-app-layout>