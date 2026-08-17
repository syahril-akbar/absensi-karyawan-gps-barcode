<x-app-layout>
  <div class="mx-auto w-full max-w-2xl px-4 pb-6 pt-6">
    <div class="flex items-center gap-3">
      <div class="flex h-11 w-11 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900">
        <x-heroicon-o-user class="h-5 w-5 text-indigo-600 dark:text-indigo-300" />
      </div>
      <div>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profil</h1>
        <p class="text-sm text-gray-500 dark:text-gray-400">Kelola informasi akun kamu</p>
      </div>
    </div>

    <!-- Profile Header Card -->
    <div class="mt-6 rounded-3xl bg-white p-6 text-center shadow-sm dark:bg-gray-800">
      <div class="mx-auto h-20 w-20">
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
          <img src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}"
            class="h-20 w-20 rounded-full object-cover ring-4 ring-indigo-100 dark:ring-indigo-900" />
        @else
          <div
            class="flex h-20 w-20 items-center justify-center rounded-full bg-indigo-600 text-2xl font-bold text-white ring-4 ring-indigo-100 dark:ring-indigo-900">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
          </div>
        @endif
      </div>
      <h2 class="mt-4 text-lg font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</h2>
      <p class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</p>
      @if (Auth::user()->division)
        <span class="mt-3 inline-block rounded-full bg-indigo-50 px-4 py-1 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/50 dark:text-indigo-300">
          {{ Auth::user()->division->name }}
        </span>
      @endif
    </div>

    <div class="mt-6 flex flex-col gap-5">
      @if (Laravel\Fortify\Features::canUpdateProfileInformation())
        @livewire('profile.update-profile-information-form')
      @endif

      @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
        @livewire('profile.update-password-form')
      @endif

      @if (Laravel\Fortify\Features::canManageTwoFactorAuthentication())
        @livewire('profile.two-factor-authentication-form')
      @endif

      @livewire('profile.logout-other-browser-sessions-form')

      @if (Laravel\Jetstream\Jetstream::hasAccountDeletionFeatures())
        @livewire('profile.delete-user-form')
      @endif
    </div>
  </div>
</x-app-layout>