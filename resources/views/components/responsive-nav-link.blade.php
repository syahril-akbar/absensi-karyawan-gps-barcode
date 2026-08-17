@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full rounded-xl px-3 py-2.5 text-start text-sm font-semibold text-indigo-700 bg-indigo-50 dark:bg-indigo-900/50 dark:text-indigo-300 focus:outline-none transition duration-150 ease-in-out'
            : 'block w-full rounded-xl px-3 py-2.5 text-start text-sm font-medium text-gray-600 dark:text-gray-400 hover:bg-gray-100 hover:text-gray-800 dark:hover:bg-gray-700 dark:hover:text-gray-200 focus:outline-none transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>