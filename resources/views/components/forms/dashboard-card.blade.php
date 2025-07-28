@props(['icon', 'color', 'label', 'value'])

<div class="flex items-center p-4 bg-white rounded-xl shadow-sm">
    <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center {{ $color }} rounded-full text-white">
        <i class="fas fa-{{ $icon }}"></i>
    </div>
    <div class="ml-4">
        <p class="text-sm text-gray-500">{{ $label }}</p>
        <p class="text-xl font-semibold text-gray-800">{{ $value }}</p>
    </div>
</div>
