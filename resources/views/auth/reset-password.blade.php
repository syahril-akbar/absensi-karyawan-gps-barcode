<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <h1 class="text-center text-xl font-bold text-gray-900 dark:text-white">Atur Ulang Kata Sandi</h1>
    <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">Buat kata sandi baru untuk akun kamu</p>

    <div class="mt-6">
      <x-validation-errors class="mb-4 rounded-2xl bg-red-50 px-4 py-3 dark:bg-red-900/30" />
    </div>

    @session('status')
      <div class="mb-4 rounded-2xl bg-green-50 px-4 py-3 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-400">
        {{ $value }}
      </div>
    @endsession

    <form method="POST" action="{{ route('password.update') }}" class="mt-4">
      @csrf

      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <div class="block">
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" class="mt-2 block w-full rounded-xl" type="email" name="email"
          :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="nama@email.com" />
      </div>

      <div class="mt-4">
        <x-label for="password" value="{{ __('Password') }}" />
        <x-input id="password" class="mt-2 block w-full rounded-xl" type="password" name="password" required
          autocomplete="new-password" placeholder="••••••••" />
      </div>

      <div class="mt-4">
        <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
        <x-input id="password_confirmation" class="mt-2 block w-full rounded-xl" type="password"
          name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
      </div>

      <x-button class="mt-6 w-full justify-center">
        {{ __('Reset Password') }}
      </x-button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
      Ingat kata sandi?
      <a href="{{ route('login') }}"
        class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400">
        {{ __('Log in') }}
      </a>
    </p>
  </x-authentication-card>
</x-guest-layout>