<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ $title ?? config('app.name', 'Laravel') }}</title>

  <!-- PWA -->
  @php($pwaEnabled = \App\Support\Settings::getBool('pwa.enabled'))
  <meta name="theme-color" content="{{ \App\Support\Settings::get('pwa.theme_color') }}">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
  <meta name="apple-mobile-web-app-title" content="{{ \App\Support\Settings::get('pwa.short_name') }}">
  @if ($pwaEnabled)
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
  @endif
  <link rel="icon" type="image/png" href="{{ asset('icons/icon-192x192.png') }}">
  <link rel="apple-touch-icon" href="{{ asset('icons/apple-touch-icon.png') }}">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Styles -->
  @livewireStyles

  @stack('styles')
</head>

<body class="font-sans antialiased">
  <x-banner />

  <div class="min-h-screen bg-gray-100 pb-16 dark:bg-gray-900 sm:bg-gray-200 sm:pb-0" x-data>
    @livewire('navigation-menu')

    <x-admin-sidebar />

    <!-- Page Heading -->
    @if (isset($header))
      <header class="bg-white shadow dark:bg-gray-800">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
          {{ $header }}
        </div>
      </header>
    @endif

    <!-- Page Content -->
    <main
      @if (Auth::user()->isAdmin)
        :class="$store.sidebar.collapsed ? 'lg:pl-20' : 'lg:pl-64'"
      @endif
      class="transition-[padding] duration-300 ease-in-out">
      {{ $slot }}
    </main>
  </div>

  <x-bottom-navigation />

  <x-sigsegv-core-dumped />

  @stack('modals')

  @livewireScripts

  @if ($pwaEnabled)
    <script>
      if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
          navigator.serviceWorker.register('{{ asset('sw.js') }}');
        });
      }
    </script>
  @endif

  @stack('scripts')
</body>

</html>
