<div {{ $attributes->merge(['class' => 'rounded-3xl bg-white p-6 shadow-sm dark:bg-gray-800']) }}>
  <div class="mb-5 border-b border-gray-100 pb-4 dark:border-gray-700">
    <h3 class="text-base font-bold text-gray-900 dark:text-white">{{ $title }}</h3>
    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ $description }}</p>
  </div>

  {{ $content }}
</div>