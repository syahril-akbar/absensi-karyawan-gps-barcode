@props([
    'active' => false,
    'align' => 'left',
    'contentClasses' => 'py-1 bg-white dark:bg-gray-700',
    'dropdownClasses' => 'w-48',
    'triggerClasses' => '',
])

@php
  switch ($align) {
      case 'left':
          $alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
          break;
      case 'top':
          $alignmentClasses = 'origin-top';
          break;
      case 'none':
      case 'false':
          $alignmentClasses = '';
          break;
      case 'right':
      default:
          $alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
          break;
  }
  $classes = $active
      ? 'relative inline-flex items-center rounded-full bg-indigo-50 px-3.5 py-2 text-sm font-semibold leading-5 text-indigo-700 transition duration-150 ease-in-out cursor-pointer dark:bg-indigo-900/50 dark:text-indigo-300'
      : 'relative inline-flex items-center rounded-full px-3.5 py-2 text-sm font-medium leading-5 text-gray-600 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-900 cursor-pointer dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-200';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }} x-data="{ open: false }" @click.away="open = false"
  @close.stop="open = false">
  <div @click="open = ! open" class="{{ $triggerClasses }} flex h-full items-center">
    {{ $trigger }}
  </div>
  <div>
    <div x-show="open" x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
      x-transition:leave-end="transform opacity-0 scale-95"
      class="{{ $alignmentClasses }} {{ $dropdownClasses }} absolute z-50 mt-2 rounded-2xl bg-white p-1.5 shadow-lg shadow-gray-200/50 ring-1 ring-gray-100 dark:bg-gray-800 dark:shadow-gray-900/50 dark:ring-gray-700"
      style="display: none;" @click="open = false">
      <div class="{{ $contentClasses }} rounded-xl">
        {{ $content }}
      </div>
    </div>
  </div>
</div>