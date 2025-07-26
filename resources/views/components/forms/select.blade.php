@props([
    'name',
    'label' => null,
    'options' => [],
    'selected' => null,
    'required' => false,
    'class' => '',
    'labelClass' => '',
])

<div class="flex flex-col mb-4">
    @if ($label)
        <label for="{{ $name }}" class="text-sm font-medium text-gray-700 mb-1 {{ $labelClass }}">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif

    <select
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
       {{ $attributes->merge(['class' => 'w-full px-4 py-2 rounded-lg text-gray-700 bg-gray-50 border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent ' . $class]) }}
    >
        @foreach($options as $key => $value)
            <option value="{{ $key }}" {{ (string) old($name, $selected) === (string) $key ? 'selected' : '' }}>
                {{ $value }}
            </option>
        @endforeach
    </select>

    @error($name)
        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
    @enderror
</div>
