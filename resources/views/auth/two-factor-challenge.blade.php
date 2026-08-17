<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <h1 class="text-center text-xl font-bold text-gray-900 dark:text-white">Verifikasi Dua Langkah</h1>

    <div x-data="{ recovery: false }">
      <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400" x-show="!recovery">
        {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
      </p>

      <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400" x-cloak x-show="recovery">
        {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
      </p>

      <div class="mt-6">
        <x-validation-errors class="mb-4 rounded-2xl bg-red-50 px-4 py-3 dark:bg-red-900/30" />
      </div>

      <form method="POST" action="{{ route('two-factor.login') }}" class="mt-4">
        @csrf

        <div class="mt-4" x-show="!recovery">
          <x-label for="code" value="{{ __('Code') }}" />
          <x-input id="code" class="mt-2 block w-full rounded-xl" type="text" inputmode="numeric" name="code" autofocus
            x-ref="code" autocomplete="one-time-code" placeholder="123456" />
        </div>

        <div class="mt-4" x-cloak x-show="recovery">
          <x-label for="recovery_code" value="{{ __('Recovery Code') }}" />
          <x-input id="recovery_code" class="mt-2 block w-full rounded-xl" type="text" name="recovery_code"
            x-ref="recovery_code" autocomplete="one-time-code" placeholder="Kode pemulihan" />
        </div>

        <div class="mt-6 flex flex-col items-stretch gap-3 sm:flex-row sm:items-center sm:justify-between">
          <button type="button"
            class="text-left text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400"
            x-show="!recovery" x-on:click="
              recovery = true;
              $nextTick(() => { $refs.recovery_code.focus() })
            ">
            {{ __('Use a recovery code') }}
          </button>

          <button type="button" x-cloak
            class="text-left text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400"
            x-show="recovery" x-on:click="
              recovery = false;
              $nextTick(() => { $refs.code.focus() })
            ">
            {{ __('Use an authentication code') }}
          </button>

          <x-button class="sm:ms-4">
            {{ __('Log in') }}
          </x-button>
        </div>
      </form>
    </div>
  </x-authentication-card>
</x-guest-layout>