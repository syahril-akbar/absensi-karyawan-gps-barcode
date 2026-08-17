@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center rounded-full bg-indigo-50 px-3.5 py-2 text-sm font-semibold leading-5 text-indigo-700 transition duration-150 ease-in-out dark:bg-indigo-900/50 dark:text-indigo-300'
            : 'inline-flex items-center rounded-full px-3.5 py-2 text-sm font-medium leading-5 text-gray-600 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>