<x-layouts.app>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tableau de bord')}}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Bienvenu dans le tableau de bord') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">

        {{-- Total Camion --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Camion') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">--</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <x-heroicon-o-arrow-up class="h-4 w-4 mr-1" />
                        {{ __('No data') }}
                    </p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                    <x-heroicon-o-truck class="h-6 w-6 text-blue-500 dark:text-blue-300" />
                </div>
            </div>
        </div>

        {{-- Total Chauffeur --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Chauffeur') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">--</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <x-heroicon-o-arrow-up class="h-4 w-4 mr-1" />
                        {{ __('No data') }}
                    </p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                    <x-heroicon-o-user class="h-6 w-6 text-orange-500 dark:text-orange-300" />
                </div>
            </div>
        </div>
        {{-- Total Revenue --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Revenue') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">--</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <x-heroicon-o-arrow-up class="h-4 w-4 mr-1" />
                        {{ __('No data') }}
                    </p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                    <x-heroicon-o-currency-dollar class="h-6 w-6 text-green-500 dark:text-green-300" />
                </div>
            </div>
        </div>

        {{-- Total Dépense --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Dépense') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">--</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <x-heroicon-o-arrow-down class="h-4 w-4 mr-1" />
                        {{ __('No data') }}
                    </p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                    <x-heroicon-o-arrow-trending-down class="h-6 w-6 text-purple-500 dark:text-purple-300" />
                </div>
            </div>
        </div>


    </div>
</x-layouts.app>
