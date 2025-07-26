@props([
    'title',
    'icon',
    'url' => '#',
    'bg' => 'bg-white',
    'color' => 'text-gray-800',
])

<a href="{{ $url }}" class="block p-6 rounded-lg shadow hover:shadow-md transition duration-300 {{ $bg }}">
    <div class="flex items-center space-x-4">
        <div class="text-3xl {{ $color }}">
            {!! $icon !!}
        </div>
        <div class="text-lg font-semibold {{ $color }}">
            {{ $title }}
        </div>
    </div>
</a>
