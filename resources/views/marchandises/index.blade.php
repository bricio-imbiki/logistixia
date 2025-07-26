<x-layouts.app>
    <!-- Flash message -->
    @if (session('success'))
        <div class="mt-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="p-6 bg-white rounded-xl shadow-md">

        <!-- Titre + Bouton Ajouter -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <x-heroicon-o-archive-box class="w-6 h-6 text-blue-600" />
                <span>Gestion des marchandises</span>
            </h2>

            <a href="{{ route('marchandises.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Ajouter
            </a>
        </div>

        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('marchandises.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Rechercher par description, lieu, etc."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                        <th class="px-4 py-2">Description</th>
                        <th class="px-4 py-2">Client</th>
                        <th class="px-4 py-2">Poids (kg)</th>
                        <th class="px-4 py-2">Volume (m³)</th>
                        <th class="px-4 py-2">Valeur estimée</th>
                        <th class="px-4 py-2">Lieu livraison</th>
                        <th class="px-4 py-2">Statut</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($marchandises as $marchandise)
                        <tr class="border-b hover:bg-gray-50 text-center">
                            <td class="px-4 py-2 text-left">{{ Str::limit($marchandise->description ?? '-', 40) }}</td>
                            <td class="px-4 py-2 text-left">{{ $marchandise->client->raison_sociale ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $marchandise->poids_kg ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $marchandise->volume_m3 ?? '-' }}</td>
                            <td class="px-4 py-2">{{ number_format($marchandise->valeur_estimee, 0, ',', ' ') ?? '-' }} Ar</td>
                            <td class="px-4 py-2 text-left">{{ $marchandise->lieu_livraison ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span @class([
                                    'bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold' => $marchandise->statut === 'chargee',
                                    'bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold' => $marchandise->statut === 'en_transit',
                                    'bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold' => $marchandise->statut === 'livree',
                                    'bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold' => $marchandise->statut === 'retour',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $marchandise->statut)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('marchandises.edit', $marchandise->id) }}"
                                       class="text-blue-600 hover:text-blue-800 transition" title="Modifier">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('marchandises.destroy', $marchandise->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer cette marchandise ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Supprimer">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-6 text-gray-500">Aucune marchandise trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $marchandises->links() }}
        </div>
    </div>
</x-layouts.app>
