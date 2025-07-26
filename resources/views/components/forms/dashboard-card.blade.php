@props(['title', 'heroicon', 'iconColor', 'bgColor', 'data'])

<div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ $title }}</p>
            <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ $data }}</p>
            <p class="text-xs text-gray-500 flex items-center mt-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
                {{ __('Aucune donnée disponible') }}
            </p>
        </div>
        <div class="{{ $bgColor }} p-3 rounded-full">
            <x-heroicon-{{ $heroicon }} class="h-6 w-6 {{ $iconColor }}" />
        </div>
    </div>
</div>
