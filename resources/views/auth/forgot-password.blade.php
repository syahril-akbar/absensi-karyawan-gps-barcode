<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <h1 class="text-center text-xl font-bold text-gray-900 dark:text-white">Lupa Kata Sandi</h1>
    <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">
      {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </p>

    <div class="mt-6">
      <x-validation-errors class="mb-4 rounded-2xl bg-red-50 px-4 py-3 dark:bg-red-900/30" />
    </div>

    @session('status')
      <div class="mb-4 rounded-2xl bg-green-50 px-4 py-3 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-400">
        {{ $value }}
      </div>
    @endsession

    <form method="POST" action="{{ route('password.email') }}" class="mt-4">
      @csrf

      <div class="block">
        <x-label for="email" value="{{ __('Email') }}" />
        <x-input id="email" class="mt-2 block w-full rounded-xl" type="email" name="email" :value="old('email')"
          required autofocus autocomplete="username" placeholder="nama@email.com" />
      </div>

      <x-button class="mt-6 w-full justify-center">
        {{ __('Email Password Reset Link') }}
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