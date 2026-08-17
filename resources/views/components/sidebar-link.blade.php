@props(['active' => false, 'label' => ''])

<a {{ $attributes->merge([
    'title' => $label,
    'class' =>
        'flex items-center rounded-xl px-3 py-2.5 text-sm font-medium transition duration-150 ' .
        ($active
            ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-500/15 dark:text-indigo-300'
            : 'text-gray-600 hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700/60 dark:hover:text-gray-200'),
]) }} :class="$store.sidebar.collapsed ? 'justify-center' : 'justify-start'">
  <span class="flex items-center gap-3">
    {{ $icon }}
    <span x-show="!$store.sidebar.collapsed" x-cloak class="whitespace-nowrap">{{ $label }}</span>
  </span>
</a>