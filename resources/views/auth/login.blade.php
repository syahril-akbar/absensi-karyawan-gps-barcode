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

    <form method="POST" action="{{ route('login') }}" class="mt-4">
      @csrf
      <input type="hidden" id="device_token" name="device_token" value="">

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

      <x-button class="mt-6 w-full justify-center">
        {{ __('Log in') }}
      </x-button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
      Belum punya akun?
      <a href="{{ route('register') }}"
        class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400">
        {{ __('Register') }}
      </a>
    </p>

    @pushOnce('scripts')
      <script>
        (function() {
          let token = localStorage.getItem('device_token');
          if (!token) {
            token = crypto.randomUUID();
            localStorage.setItem('device_token', token);
          }
          document.getElementById('device_token').value = token;
        })();
      </script>
    @endPushOnce
  </x-authentication-card>
</x-guest-layout>