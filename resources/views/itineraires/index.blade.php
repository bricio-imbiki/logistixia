<x-layouts.app>
     <!-- Flash message -->
        @if (session('success'))
            <div class="mt-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow">
                {{ session('success') }}
            </div>
        @endif
    <div class="p-6 bg-white rounded-xl shadow-md">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <x-heroicon-o-map class="w-6 h-6 text-blue-600" />
                <span>Gestion des itinéraires</span>
            </h2>

            <a href="{{ route('itineraires.create') }}"
               class=" no-underline inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Ajouter
            </a>
        </div>


        <form method="GET" action="{{ route('itineraires.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Rechercher par lieu de départ ou arrivée"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                        <th class="px-4 py-2">Lieu départ</th>
                        <th class="px-4 py-2">Lieu arrivée</th>
                        <th class="px-4 py-2">Distance (km)</th>
                        <th class="px-4 py-2">Durée estimée (h)</th>
                        <th class="px-4 py-2">Péage estimé</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
              <tbody>
    @forelse ($itineraires as $itineraire)
        <tr class="border-b hover:bg-gray-50 text-center">
            <td class="px-4 py-2">{{ $itineraire->lieu_depart ?? '-' }}</td>
            <td class="px-4 py-2">{{ $itineraire->lieu_arrivee ?? '-' }}</td>
            <td class="px-4 py-2">{{ $itineraire->distance_km ?? '-' }}</td>
            <td class="px-4 py-2">{{ $itineraire->duree_estimee_h ?? '-' }}</td>
            <td class="px-4 py-2">{{ $itineraire->peage_estime ?? '-' }}</td>
            <td class="px-4 py-2">
                <div class="inline-flex items-center gap-2 justify-center">
                    <a href="{{ route('itineraires.edit', $itineraire) }}"
                       class="text-blue-600 hover:text-blue-800" title="Modifier">
                        <x-heroicon-o-pencil class="w-5 h-5" />
                    </a>
                    <form action="{{ route('itineraires.destroy', $itineraire) }}" method="POST"
                          onsubmit="return confirm('Supprimer cet itinéraire ?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                            <x-heroicon-o-trash class="w-5 h-5" />
                        </button>
                    </form>
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="6" class="text-center py-6 text-gray-500">Aucun itinéraire trouvé.</td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>

        <div class="mt-6">
            {{ $itineraires->links() }}
        </div>

    </div>
</x-layouts.app>
