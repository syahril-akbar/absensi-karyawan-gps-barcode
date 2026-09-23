<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <h1 class="text-center text-xl font-bold text-gray-900 dark:text-white">Selamat Datang</h1>
    <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">Masuk untuk mulai absensi</p>

    <div class="mt-6">
      <x-validation-errors class="mb-4 rounded-2xl bg-red-50 px-4 py-3 dark:bg-red-900/30" />
    </div>

    @session('status')
      <div class="mb-4 text-sm font-medium text-green-600 dark:text-green-400">
        {{ $value }}
      </div>
    @endsession

    <form method="POST" action="{{ route('login') }}" class="mt-4" x-data="{ submitting: false }" @submit="if (submitting) { $event.preventDefault(); return false; } submitting = true;">
      @csrf
      <input type="hidden" id="device_token" name="device_token" value="">
      <script>
        (function() {
          try {
            var token = localStorage.getItem('device_token');
            if (!token) {
              token = (typeof crypto !== 'undefined' && typeof crypto.randomUUID === 'function')
                ? crypto.randomUUID()
                : 'dev-' + Math.random().toString(36).substring(2) + Date.now().toString(36);
              localStorage.setItem('device_token', token);
            }
            document.getElementById('device_token').value = token;
          } catch (e) {}
        })();
      </script>

      <div>
        <x-label for="email" value="{{ __('Email or Phone') }}" />
        <x-input id="email" class="mt-2 block w-full rounded-xl" type="text" name="email" :value="old('email')"
          required autofocus autocomplete="username" placeholder="nama@email.com / nomor HP" />
      </div>

      <div class="mt-4">
        <x-label for="password" value="{{ __('Password') }}" />
        <x-input id="password" class="mt-2 block w-full rounded-xl" type="password" name="password" required
          autocomplete="current-password" placeholder="••••••••" />
      </div>

      <div class="mt-4 flex items-center justify-between">
        <label for="remember_me" class="flex items-center">
          <x-checkbox id="remember_me" name="remember" checked />
          <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
        </label>

        @if (Route::has('password.request'))
          <a class="text-sm text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400"
            href="{{ route('password.request') }}">
            {{ __('Forgot your password?') }}
          </a>
        @endif
      </div>

      <x-button class="mt-6 w-full justify-center" ::disabled="submitting" x-bind:class="{ 'opacity-50 cursor-not-allowed': submitting }">
        <span x-show="!submitting">{{ __('Log in') }}</span>
        <span x-show="submitting" class="inline-flex items-center" style="display: none;">
          <svg class="mr-2 h-4 w-4 animate-spin text-white dark:text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          Memproses...
        </span>
      </x-button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
      Belum punya akun?
      <a href="{{ route('register') }}"
        class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400">
        {{ __('Register') }}
      </a>
    </p>
  </x-authentication-card>
</x-guest-layout>