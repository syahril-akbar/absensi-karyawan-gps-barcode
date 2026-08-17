<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

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
</head>

<body class="font-sans antialiased">
  <div
    class="relative flex min-h-screen flex-col items-center justify-center overflow-hidden bg-gray-50 px-4 py-10 dark:bg-gray-900">
    <div
      class="pointer-events-none absolute -left-24 -top-24 h-72 w-72 rounded-full bg-indigo-100 blur-3xl dark:bg-indigo-900/40">
    </div>
    <div
      class="pointer-events-none absolute -bottom-24 -right-24 h-72 w-72 rounded-full bg-purple-100 blur-3xl dark:bg-purple-900/40">
    </div>

    <div class="absolute right-4 top-4 z-10">
      <x-theme-toggle x-data />
    </div>

    {{ $slot }}
  </div>

  <x-sigsegv-core-dumped />

  @livewireScripts

  @if (\App\Support\Settings::getBool('pwa.enabled'))
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