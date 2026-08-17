@if (!Auth::user()->isAdmin)
  <nav
    class="fixed inset-x-0 bottom-0 z-40 border-t border-gray-200 bg-white/95 pb-[env(safe-area-inset-bottom)] backdrop-blur dark:border-gray-700 dark:bg-gray-800/95 sm:hidden">
    <div class="grid grid-cols-5">
      <a href="{{ route('home') }}"
        class="flex flex-col items-center gap-1 py-2 text-xs font-medium {{ request()->routeIs('home') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-home class="h-6 w-6" />
        Beranda
      </a>
      <a href="{{ route('attendance-history') }}"
        class="flex flex-col items-center gap-1 py-2 text-xs font-medium {{ request()->routeIs('attendance-history') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-clock class="h-6 w-6" />
        Riwayat
      </a>
      <a href="{{ route('apply-leave') }}"
        class="flex flex-col items-center gap-1 py-2 text-xs font-medium {{ request()->routeIs('apply-leave') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-envelope-open class="h-6 w-6" />
        <span class="whitespace-nowrap">Ajukan Izin</span>
      </a>
      <a href="{{ route('leave-history') }}"
        class="flex flex-col items-center gap-1 py-2 text-xs font-medium {{ request()->routeIs('leave-history') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-document-text class="h-6 w-6" />
        <span class="whitespace-nowrap">Riwayat Izin</span>
      </a>
      <a href="{{ route('profile.show') }}"
        class="flex flex-col items-center gap-1 py-2 text-xs font-medium {{ request()->routeIs('profile.show') ? 'text-indigo-600 dark:text-indigo-400' : 'text-gray-500 dark:text-gray-400' }}">
        <x-heroicon-o-user class="h-6 w-6" />
        Profil
      </a>
    </div>
  </nav>
@endif