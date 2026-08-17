@props(['active' => false])
<a
  {{ $attributes->merge(['class' => 'block w-full rounded-lg px-3 py-2 text-start text-sm font-medium leading-5 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-700 transition duration-150 ease-in-out ' . ($active ? 'bg-indigo-50 text-indigo-700 dark:bg-indigo-900/50 dark:text-indigo-300' : 'text-gray-700 dark:text-gray-300')]) }}>
  {{ $slot }}
</a>