@if (Auth::user()->isAdmin)
  <aside x-data
    :class="$store.sidebar.collapsed ? 'lg:w-20' : 'lg:w-64'"
    class="fixed bottom-0 start-0 top-16 z-30 hidden w-64 flex-col border-r border-gray-200 bg-white transition-[width] duration-300 ease-in-out dark:border-gray-700 dark:bg-gray-800 lg:flex">

    {{-- Nav --}}
    <nav class="flex-1 space-y-1 overflow-y-auto overflow-x-hidden px-3 py-4">

      {{-- Menu Utama --}}
      <div x-show="!$store.sidebar.collapsed" x-cloak class="px-2 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Menu Utama</div>
      <x-sidebar-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" label="Beranda">
        <x-slot name="icon"><x-heroicon-o-squares-2x2 class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.barcodes') }}" :active="request()->routeIs('admin.barcodes')" label="Barcode">
        <x-slot name="icon"><x-heroicon-o-qr-code class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.attendances') }}" :active="request()->routeIs('admin.attendances')" label="Absensi">
        <x-slot name="icon"><x-heroicon-o-clock class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.employees') }}" :active="request()->routeIs('admin.employees')" label="Karyawan">
        <x-slot name="icon"><x-heroicon-o-users class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.leave-requests') }}" :active="request()->routeIs('admin.leave-requests')" label="Ajukan Izin">
        <x-slot name="icon"><x-heroicon-o-envelope-open class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>

      {{-- Master Data --}}
      <div x-show="!$store.sidebar.collapsed" x-cloak class="mt-3 border-t border-gray-100 px-2 pb-1 pt-3 dark:border-gray-700"></div>
      <div x-show="!$store.sidebar.collapsed" x-cloak class="px-2 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Data Master</div>
      <x-sidebar-link href="{{ route('admin.masters.division') }}" :active="request()->routeIs('admin.masters.division')" label="Divisi">
        <x-slot name="icon"><x-heroicon-o-building-office class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.masters.job-title') }}" :active="request()->routeIs('admin.masters.job-title')" label="Jabatan">
        <x-slot name="icon"><x-heroicon-o-briefcase class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.masters.education') }}" :active="request()->routeIs('admin.masters.education')" label="Pendidikan">
        <x-slot name="icon"><x-heroicon-o-academic-cap class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.masters.shift') }}" :active="request()->routeIs('admin.masters.shift')" label="Shift">
        <x-slot name="icon"><x-heroicon-o-bolt class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.masters.holiday') }}" :active="request()->routeIs('admin.masters.holiday')" label="Hari Libur">
        <x-slot name="icon"><x-heroicon-o-calendar-days class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.masters.admin') }}" :active="request()->routeIs('admin.masters.admin')" label="Manajemen Admin">
        <x-slot name="icon"><x-heroicon-o-shield-check class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>

      {{-- Import & Export --}}
      <div x-show="!$store.sidebar.collapsed" x-cloak class="mt-3 border-t border-gray-100 px-2 pb-1 pt-3 dark:border-gray-700"></div>
      <div x-show="!$store.sidebar.collapsed" x-cloak class="px-2 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Impor & Ekspor</div>
      <x-sidebar-link href="{{ route('admin.import-export.users') }}" :active="request()->routeIs('admin.import-export.users')" label="Karyawan / Admin">
        <x-slot name="icon"><x-heroicon-o-arrow-down-tray class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.import-export.attendances') }}" :active="request()->routeIs('admin.import-export.attendances')" label="Absensi">
        <x-slot name="icon"><x-heroicon-o-arrow-up-tray class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>

      {{-- Pengaturan --}}
      <div x-show="!$store.sidebar.collapsed" x-cloak class="mt-3 border-t border-gray-100 px-2 pb-1 pt-3 dark:border-gray-700"></div>
      <div x-show="!$store.sidebar.collapsed" x-cloak class="px-2 pb-1 pt-2 text-[11px] font-bold uppercase tracking-wider text-gray-400 dark:text-gray-500">Pengaturan</div>
      <x-sidebar-link href="{{ route('admin.settings') }}" :active="request()->routeIs('admin.settings')" label="Pengaturan Umum">
        <x-slot name="icon"><x-heroicon-o-cog-6-tooth class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>
      <x-sidebar-link href="{{ route('admin.pwa-settings') }}" :active="request()->routeIs('admin.pwa-settings')" label="Pengaturan PWA">
        <x-slot name="icon"><x-heroicon-o-device-phone-mobile class="h-5 w-5 shrink-0" /></x-slot>
      </x-sidebar-link>

    </nav>

    {{-- Footer --}}
    <div class="shrink-0 border-t border-gray-100 p-3 dark:border-gray-700">
      <button @click="$store.sidebar.toggle()"
        :title="$store.sidebar.collapsed ? 'Lebarkan sidebar' : 'Ciutkan sidebar'"
        :class="$store.sidebar.collapsed ? 'justify-center' : 'justify-start'"
        class="flex w-full items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-medium text-gray-600 transition hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700">
        <x-heroicon-o-chevron-right class="h-5 w-5 shrink-0 transition-transform duration-300" x-bind:class="$store.sidebar.collapsed ? '' : 'rotate-180'" />
        <span x-show="!$store.sidebar.collapsed" x-cloak class="whitespace-nowrap">Ciutkan</span>
      </button>
    </div>
  </aside>
@endif