<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <h1 class="text-center text-xl font-bold text-gray-900 dark:text-white">Konfirmasi Kata Sandi</h1>
    <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">
      {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
    </p>

    <div class="mt-6">
      <x-validation-errors class="mb-4 rounded-2xl bg-red-50 px-4 py-3 dark:bg-red-900/30" />
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="mt-4">
      @csrf

      <div>
        <x-label for="password" value="{{ __('Password') }}" />
        <x-input id="password" class="mt-2 block w-full rounded-xl" type="password" name="password" required
          autocomplete="current-password" autofocus placeholder="••••••••" />
      </div>

      <x-button class="mt-6 w-full justify-center">
        {{ __('Confirm') }}
      </x-button>
    </form>
  </x-authentication-card>
</x-guest-layout>