<nav x-data="{ open: false }" class="sticky top-0 z-40 border-b border-gray-100 bg-white/90 backdrop-blur-md dark:border-gray-700 dark:bg-gray-800/90">
  <!-- Primary Navigation Menu -->
  <div class="mx-auto max-w-7xl px-4 sm:px-6 {{ Auth::user()->isAdmin ? 'lg:max-w-none lg:px-0' : 'lg:px-8' }}">
    <div class="flex h-16 justify-between">
      <div class="flex items-center gap-2 {{ Auth::user()->isAdmin ? 'lg:ps-3' : '' }}">
        <!-- Logo + App Name -->
        <a href="{{ Auth::user()->isAdmin ? route('admin.dashboard') : route('home') }}" class="flex items-center gap-2">
          <x-application-mark class="h-10 w-auto object-contain" />
          <span class="hidden whitespace-nowrap text-base font-bold text-gray-900 dark:text-white sm:inline">Absensi Karyawan</span>
        </a>

        <!-- Navigation Links -->
        <div class="hidden space-x-1 sm:ms-4 sm:flex lg:ms-6 {{ Auth::user()->isAdmin ? 'lg:hidden' : '' }}">
          @if (Auth::user()->isAdmin)
            <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
              {{ __('Beranda') }}
            </x-nav-link>
            <x-nav-link href="{{ route('admin.barcodes') }}" :active="request()->routeIs('admin.barcodes')">
              {{ __('Barcode') }}
            </x-nav-link>
            <x-nav-link class="hidden md:inline-flex" href="{{ route('admin.attendances') }}" :active="request()->routeIs('admin.attendances')">
              {{ __('Absensi') }}
            </x-nav-link>
            <x-nav-link class="hidden md:inline-flex" href="{{ route('admin.employees') }}" :active="request()->routeIs('admin.employees')">
              {{ __('Karyawan') }}
            </x-nav-link>
            <x-nav-link class="hidden md:inline-flex" href="{{ route('admin.leave-requests') }}" :active="request()->routeIs('admin.leave-requests')">
              {{ __('Pengajuan Izin') }}
            </x-nav-link>
            <x-nav-dropdown :active="request()->routeIs('admin.masters.*')" triggerClasses="text-nowrap">
              <x-slot name="trigger">
                {{ __('Data Master') }}
                <x-heroicon-o-chevron-down class="ms-1 h-4 w-4 text-gray-400" />
              </x-slot>
              <x-slot name="content">
                <x-dropdown-link class="md:hidden" href="{{ route('admin.attendances') }}" :active="request()->routeIs('admin.attendances')">
                  <x-heroicon-o-clock class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Absensi') }}
                </x-dropdown-link>
                <x-dropdown-link class="md:hidden" href="{{ route('admin.employees') }}" :active="request()->routeIs('admin.employees')">
                  <x-heroicon-o-users class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Karyawan') }}
                </x-dropdown-link>
                <x-dropdown-link class="md:hidden" href="{{ route('admin.leave-requests') }}" :active="request()->routeIs('admin.leave-requests')">
                  <x-heroicon-o-envelope-open class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Pengajuan Izin') }}
                </x-dropdown-link>
                <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                <x-dropdown-link href="{{ route('admin.masters.division') }}" :active="request()->routeIs('admin.masters.division')">
                  <x-heroicon-o-building-office class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Divisi') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.masters.job-title') }}" :active="request()->routeIs('admin.masters.job-title')">
                  <x-heroicon-o-briefcase class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Jabatan') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.masters.education') }}" :active="request()->routeIs('admin.masters.education')">
                  <x-heroicon-o-academic-cap class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Pendidikan') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.masters.shift') }}" :active="request()->routeIs('admin.masters.shift')">
                  <x-heroicon-o-bolt class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Shift') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.masters.holiday') }}" :active="request()->routeIs('admin.masters.holiday')">
                  <x-heroicon-o-calendar-days class="mr-2 h-4 w-4 text-gray-400" />
                  Hari Libur
                </x-dropdown-link>
                <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>
                <x-dropdown-link href="{{ route('admin.masters.admin') }}" :active="request()->routeIs('admin.masters.admin')">
                  <x-heroicon-o-shield-check class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Admin') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.settings') }}" :active="request()->routeIs('admin.settings')">
                  <x-heroicon-o-cog-6-tooth class="mr-2 h-4 w-4 text-gray-400" />
                  Pengaturan Umum
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.pwa-settings') }}" :active="request()->routeIs('admin.pwa-settings')">
                  <x-heroicon-o-device-phone-mobile class="mr-2 h-4 w-4 text-gray-400" />
                  Pengaturan PWA
                </x-dropdown-link>
              </x-slot>
            </x-nav-dropdown>
            <x-nav-dropdown :active="request()->routeIs('admin.import-export.*')" triggerClasses="text-nowrap">
              <x-slot name="trigger">
                {{ __('Impor & Ekspor') }}
                <x-heroicon-o-chevron-down class="ms-1 h-4 w-4 text-gray-400" />
              </x-slot>
              <x-slot name="content">
                <x-dropdown-link href="{{ route('admin.import-export.users') }}" :active="request()->routeIs('admin.import-export.users')">
                  <x-heroicon-o-arrow-down-tray class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Karyawan') }}/{{ __('Admin') }}
                </x-dropdown-link>
                <x-dropdown-link href="{{ route('admin.import-export.attendances') }}" :active="request()->routeIs('admin.import-export.attendances')">
                  <x-heroicon-o-arrow-up-tray class="mr-2 h-4 w-4 text-gray-400" />
                  {{ __('Absensi') }}
                </x-dropdown-link>
              </x-slot>
            </x-nav-dropdown>
          @else
            <x-nav-link href="{{ route('home') }}" :active="request()->routeIs('home')">
              {{ __('Beranda') }}
            </x-nav-link>
          @endif
        </div>
      </div>

      <div class="flex items-center gap-1 {{ Auth::user()->isAdmin ? 'lg:pe-4' : '' }}">
        <div class="hidden sm:ms-4 sm:flex sm:items-center sm:gap-1">
          <x-install-prompt compact />

          <x-theme-toggle />

          <!-- Settings Dropdown -->
          <div class="relative ms-1">
            <x-dropdown align="right" width="48">
              <x-slot name="trigger">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                  <button
                    class="flex items-center gap-2 rounded-full p-1 transition hover:bg-gray-100 focus:outline-none dark:hover:bg-gray-700">
                    <img class="h-9 w-9 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                      alt="{{ Auth::user()->name }}" />
                    <span class="hidden text-sm font-medium text-gray-700 dark:text-gray-200 lg:inline">{{ Auth::user()->name }}</span>
                  </button>
                @else
                  <span class="inline-flex rounded-md">
                    <button type="button"
                      class="inline-flex items-center gap-2 rounded-full bg-gray-100 px-3 py-2 text-sm font-medium leading-4 text-gray-700 transition duration-150 ease-in-out hover:bg-gray-200 focus:outline-none dark:bg-gray-700 dark:text-gray-200 dark:hover:bg-gray-600">
                      <span class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                      </span>
                      <span class="hidden lg:inline">{{ Auth::user()->name }}</span>

                      <svg class="-me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                      </svg>
                    </button>
                  </span>
                @endif
              </x-slot>

              <x-slot name="content">
                <!-- Account Management -->
                <div class="flex items-center gap-3 px-3 py-2.5">
                  @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                      alt="{{ Auth::user()->name }}" />
                  @else
                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                      {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                  @endif
                  <div class="min-w-0">
                    <div class="truncate text-sm font-semibold text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
                    <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                  </div>
                </div>

                <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>

                <x-dropdown-link href="{{ route('profile.show') }}">
                  {{ __('Profil') }}
                </x-dropdown-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                  <x-dropdown-link href="{{ route('api-tokens.index') }}">
                    {{ __('Token API') }}
                  </x-dropdown-link>
                @endif

                <div class="my-1 border-t border-gray-100 dark:border-gray-700"></div>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                  @csrf

                  <x-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                    {{ __('Keluar') }}
                  </x-dropdown-link>
                </form>
              </x-slot>
            </x-dropdown>
          </div>
        </div>

        <x-install-prompt compact class="sm:hidden" />

        <x-theme-toggle class="sm:hidden" />

        @if (!Auth::user()->isAdmin)
          <form method="POST" action="{{ route('logout') }}" x-data class="flex items-center sm:hidden">
            @csrf
            <button type="submit"
              class="inline-flex items-center justify-center rounded-full p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-300">
              <x-heroicon-o-arrow-right-start-on-rectangle class="h-5 w-5" />
            </button>
          </form>
        @endif

        <!-- Hamburger (admin only) -->
        @if (Auth::user()->isAdmin)
          <div class="-me-2 flex items-center sm:hidden">
            <button @click="open = ! open"
              class="inline-flex items-center justify-center rounded-full p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none dark:text-gray-500 dark:hover:bg-gray-700 dark:hover:text-gray-300 dark:focus:bg-gray-700 dark:focus:text-gray-300">
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round"
                  stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                  stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Responsive Navigation Menu (admin only) -->
  @if (Auth::user()->isAdmin)
    <div :class="{ 'block': open, 'hidden': !open }"
      class="max-h-[calc(100dvh-4rem)] overflow-y-auto border-t border-gray-100 bg-white/95 backdrop-blur-md dark:border-gray-700 dark:bg-gray-800/95 sm:hidden">
      <div class="space-y-1 px-3 py-3">
        {{-- Profil --}}
        <div class="mb-2 flex items-center gap-3 rounded-2xl bg-gray-50 px-3 py-3 dark:bg-gray-900/50">
          @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div class="shrink-0">
              <img class="h-10 w-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}"
                alt="{{ Auth::user()->name }}" />
            </div>
          @else
            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
              {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
          @endif
          <div class="min-w-0">
            <div class="truncate text-sm font-bold text-gray-900 dark:text-white">{{ Auth::user()->name }}</div>
            <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
          </div>
        </div>

        {{-- Menu Utama --}}
        <div class="px-3 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          Menu Utama
        </div>
        <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
          <span class="flex items-center gap-3"><x-heroicon-o-squares-2x2 class="h-5 w-5 shrink-0" />Beranda</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.barcodes') }}" :active="request()->routeIs('admin.barcodes')">
          <span class="flex items-center gap-3"><x-heroicon-o-qr-code class="h-5 w-5 shrink-0" />Barcode</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.attendances') }}" :active="request()->routeIs('admin.attendances')">
          <span class="flex items-center gap-3"><x-heroicon-o-clock class="h-5 w-5 shrink-0" />Absensi</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.employees') }}" :active="request()->routeIs('admin.employees')">
          <span class="flex items-center gap-3"><x-heroicon-o-users class="h-5 w-5 shrink-0" />Karyawan</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.leave-requests') }}" :active="request()->routeIs('admin.leave-requests')">
          <span class="flex items-center gap-3"><x-heroicon-o-envelope-open class="h-5 w-5 shrink-0" />Ajukan Izin</span>
        </x-responsive-nav-link>

        {{-- Data Master --}}
        <div class="mt-3 border-t border-gray-100 px-3 pt-3 dark:border-gray-700"></div>
        <div class="px-3 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          Data Master
        </div>
        <x-responsive-nav-link href="{{ route('admin.masters.division') }}" :active="request()->routeIs('admin.masters.division')">
          <span class="flex items-center gap-3"><x-heroicon-o-building-office class="h-5 w-5 shrink-0" />Divisi</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.job-title') }}" :active="request()->routeIs('admin.masters.job-title')">
          <span class="flex items-center gap-3"><x-heroicon-o-briefcase class="h-5 w-5 shrink-0" />Jabatan</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.education') }}" :active="request()->routeIs('admin.masters.education')">
          <span class="flex items-center gap-3"><x-heroicon-o-academic-cap class="h-5 w-5 shrink-0" />Pendidikan</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.shift') }}" :active="request()->routeIs('admin.masters.shift')">
          <span class="flex items-center gap-3"><x-heroicon-o-bolt class="h-5 w-5 shrink-0" />Shift</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.holiday') }}" :active="request()->routeIs('admin.masters.holiday')">
          <span class="flex items-center gap-3"><x-heroicon-o-calendar-days class="h-5 w-5 shrink-0" />Hari Libur</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.masters.admin') }}" :active="request()->routeIs('admin.masters.admin')">
          <span class="flex items-center gap-3"><x-heroicon-o-shield-check class="h-5 w-5 shrink-0" />Manajemen Admin</span>
        </x-responsive-nav-link>

        {{-- Impor & Ekspor --}}
        <div class="mt-3 border-t border-gray-100 px-3 pt-3 dark:border-gray-700"></div>
        <div class="px-3 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          Impor & Ekspor
        </div>
        <x-responsive-nav-link href="{{ route('admin.import-export.users') }}" :active="request()->routeIs('admin.import-export.users')">
          <span class="flex items-center gap-3"><x-heroicon-o-arrow-down-tray class="h-5 w-5 shrink-0" />Karyawan / Admin</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.import-export.attendances') }}" :active="request()->routeIs('admin.import-export.attendances')">
          <span class="flex items-center gap-3"><x-heroicon-o-arrow-up-tray class="h-5 w-5 shrink-0" />Absensi</span>
        </x-responsive-nav-link>

        {{-- Pengaturan --}}
        <div class="mt-3 border-t border-gray-100 px-3 pt-3 dark:border-gray-700"></div>
        <div class="px-3 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          Pengaturan
        </div>
        <x-responsive-nav-link href="{{ route('admin.settings') }}" :active="request()->routeIs('admin.settings')">
          <span class="flex items-center gap-3"><x-heroicon-o-cog-6-tooth class="h-5 w-5 shrink-0" />Pengaturan Umum</span>
        </x-responsive-nav-link>
        <x-responsive-nav-link href="{{ route('admin.pwa-settings') }}" :active="request()->routeIs('admin.pwa-settings')">
          <span class="flex items-center gap-3"><x-heroicon-o-device-phone-mobile class="h-5 w-5 shrink-0" />Pengaturan PWA</span>
        </x-responsive-nav-link>

        {{-- Akun --}}
        <div class="mt-3 border-t border-gray-100 px-3 pt-3 dark:border-gray-700"></div>
        <div class="px-3 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">
          Akun
        </div>
        <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
          <span class="flex items-center gap-3"><x-heroicon-o-user-circle class="h-5 w-5 shrink-0" />Profil</span>
        </x-responsive-nav-link>

        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
          <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
            <span class="flex items-center gap-3"><x-heroicon-o-key class="h-5 w-5 shrink-0" />Token API</span>
          </x-responsive-nav-link>
        @endif

        <form method="POST" action="{{ route('logout') }}" x-data>
          @csrf
          <x-responsive-nav-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
            <span class="flex items-center gap-3"><x-heroicon-o-arrow-right-start-on-rectangle class="h-5 w-5 shrink-0" />Keluar</span>
          </x-responsive-nav-link>
        </form>
      </div>
    </div>
  @endif
</nav>