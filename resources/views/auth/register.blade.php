@php($registrationEnabled = \App\Support\Settings::getBool('registration.enabled'))

@if (!$registrationEnabled)
  <x-guest-layout>
    <x-authentication-card>
      <x-slot name="logo">
        <x-authentication-card-logo />
      </x-slot>

      <div class="flex flex-col items-center text-center">
        <div
          class="flex h-16 w-16 items-center justify-center rounded-2xl bg-red-100 text-red-600 dark:bg-red-500/20 dark:text-red-400">
          <x-heroicon-o-lock-closed class="h-8 w-8" />
        </div>
        <h1 class="mt-4 text-xl font-bold text-gray-900 dark:text-white">Pendaftaran Ditutup</h1>
        <p class="mt-2 text-sm leading-relaxed text-gray-500 dark:text-gray-400">
          {{ \App\Support\Settings::get('registration.message') }}
        </p>
        <a href="{{ route('login') }}"
          class="mt-6 inline-flex w-full items-center justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
          <x-heroicon-o-arrow-left class="mr-2 h-4 w-4" />
          Kembali ke Login
        </a>
      </div>
    </x-authentication-card>
  </x-guest-layout>
@else
<x-guest-layout>
  <x-authentication-card>
    <x-slot name="logo">
      <x-authentication-card-logo />
    </x-slot>

    <h1 class="text-center text-xl font-bold text-gray-900 dark:text-white">Buat Akun</h1>
    <p class="mt-1 text-center text-sm text-gray-500 dark:text-gray-400">Daftar untuk mulai absensi</p>

    <div class="mt-6">
      <x-validation-errors class="mb-4 rounded-2xl bg-red-50 px-4 py-3 dark:bg-red-900/30" />
    </div>

    <form method="POST" action="{{ route('register') }}" class="mt-4">
      @csrf

      <div>
        <x-label for="name" value="{{ __('Name') }}" />
        <x-input id="name" class="mt-2 block w-full rounded-xl" type="text" name="name" :value="old('name')" required
          autofocus autocomplete="name" placeholder="Nama lengkap" />
      </div>

      <div class="mt-4">
        <x-label for="nip" value="{{ __('NIP') }}" />
        <x-input id="nip" class="mt-2 block w-full rounded-xl" type="text" name="nip" :value="old('nip')"
          autocomplete="nip" placeholder="Nomor Induk Pegawai" />
      </div>

      <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <x-label for="email" value="{{ __('Email') }}" />
          <x-input id="email" class="mt-2 block w-full rounded-xl" type="email" name="email" :value="old('email')"
            required autocomplete="username" placeholder="nama@email.com" />
        </div>
        <div>
          <x-label for="phone" value="{{ __('Phone Number') }}" />
          <x-input id="phone" class="mt-2 block w-full rounded-xl" type="number" name="phone" :value="old('phone')"
            required autocomplete="username" placeholder="08xxxxxxxxxx" />
        </div>
      </div>

      <div class="mt-4">
        <x-label for="gender" value="{{ __('Gender') }}" />
        <x-select id="gender" class="mt-2 block w-full rounded-xl" name="gender" required>
          <option disabled selected>{{ __('Select Gender') }}</option>
          <option value="male">
            {{ __('Male') }}
          </option>
          <option value="female">
            {{ __('Female') }}
          </option>
        </x-select>
      </div>

      <div class="mt-4">
        <x-label for="address" value="{{ __('Address') }}" />
        <x-textarea id="address" class="mt-2 block w-full rounded-xl" name="address" :value="old('address')"
          required placeholder="Alamat lengkap" />
      </div>

      <div class="mt-4">
        <x-label for="city" value="{{ __('City') }}" />
        <x-input id="city" class="mt-2 block w-full rounded-xl" type="text" name="city" :value="old('city')"
          required autocomplete="city" placeholder="Nama kota" />
      </div>

      <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
          <x-label for="password" value="{{ __('Password') }}" />
          <x-input id="password" class="mt-2 block w-full rounded-xl" type="password" name="password" required
            autocomplete="new-password" placeholder="••••••••" />
        </div>
        <div>
          <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
          <x-input id="password_confirmation" class="mt-2 block w-full rounded-xl" type="password"
            name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
        </div>
      </div>

      @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
          <x-label for="terms">
            <div class="flex items-center">
              <x-checkbox name="terms" id="terms" required />

              <div class="ms-2">
                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                    'terms_of_service' =>
                        '<a target="_blank" href="' .
                        route('terms.show') .
                        '" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">' .
                        __('Terms of Service') .
                        '</a>',
                    'privacy_policy' =>
                        '<a target="_blank" href="' .
                        route('policy.show') .
                        '" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">' .
                        __('Privacy Policy') .
                        '</a>',
                ]) !!}
              </div>
            </div>
          </x-label>
        </div>
      @endif

      <x-button class="mt-6 w-full justify-center">
        {{ __('Register') }}
      </x-button>
    </form>

    <p class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400">
      Sudah punya akun?
      <a href="{{ route('login') }}"
        class="font-semibold text-indigo-600 hover:text-indigo-500 hover:underline dark:text-indigo-400">
        {{ __('Log in') }}
      </a>
    </p>
  </x-authentication-card>
</x-guest-layout>
@endif