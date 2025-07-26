<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier le camion</h2>
        @include('camions._form', ['camion' => $camion])
    </div>
</x-layouts.app>
