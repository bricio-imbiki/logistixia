@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
])

@if ($label)
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ $label }}</label>
@endif

<div class="flex gap-4">
    @foreach ($options as $optionValue => $optionLabel)
        <label class="flex items-center">
            <input
                type="radio"
                name="{{ $name }}"
                id="{{ $name }}_{{ $optionValue }}"
                value="{{ $optionValue }}"
                {{ (string) old($name, $selected) === (string) $optionValue ? 'checked' : '' }}
                class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500"
            >
            <span class="ml-2 text-sm text-gray-700">{{ $optionLabel }}</span>
        </label>
    @endforeach
</div>

@error($name)
    <span class="text-red-500 text-sm">{{ $message }}</span>
@enderror
