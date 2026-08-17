<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <h1 class="text-center text-xl font-bold text-gray-900 dark:text-white">Verifikasi Email</h1>
    <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">
      {{ __('Before continuing, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </p>

    @if (session('status') == 'verification-link-sent')
      <div class="mt-4 rounded-2xl bg-green-50 px-4 py-3 text-sm font-medium text-green-600 dark:bg-green-900/30 dark:text-green-400">
        {{ __('A new verification link has been sent to the email address you provided in your profile settings.') }}
      </div>
    @endif

    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
      <form method="POST" action="{{ route('verification.send') }}">
        @csrf

        <x-button type="submit" class="w-full justify-center">
          {{ __('Resend Verification Email') }}
        </x-button>
      </form>

      <div class="flex items-center justify-between gap-3">
        <a href="{{ route('profile.show') }}"
          class="text-sm font-medium text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400">
          {{ __('Edit Profile') }}
        </a>

        <form method="POST" action="{{ route('logout') }}">
          @csrf

          <button type="submit"
            class="text-sm font-medium text-gray-500 hover:text-gray-700 hover:underline dark:text-gray-400 dark:hover:text-gray-200">
            {{ __('Log Out') }}
          </button>
        </form>
      </div>
    </div>
  </x-authentication-card>
</x-guest-layout>