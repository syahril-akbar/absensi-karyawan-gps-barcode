<x-app-layout>
  <div class="mx-auto w-full max-w-lg px-4 pb-6 pt-6">
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
          {{ now()->translatedFormat('l, d F Y') }}
        </p>
        <h1 class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
          Halo, {{ Auth::user()->name }} 👋
        </h1>
      </div>
      <div class="shrink-0">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
          <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
            class="h-12 w-12 rounded-full object-cover ring-2 ring-indigo-500/40" />
        @else
          <div
            class="flex h-12 w-12 items-center justify-center rounded-full bg-indigo-600 text-lg font-bold text-white">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
        @endif
      </div>
    </div>

    <div class="mt-6">
      <x-install-prompt />
    </div>

    @livewire('scan-component')
  </div>
</x-app-layout>