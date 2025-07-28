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
                <x-heroicon-o-fire class="w-6 h-6 text-orange-500" />
                <span>Suivi Carburant</span>
            </h2>

            <a href="{{ route('carburants.create') }}"
               class="no-underline inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Ajouter
            </a>
        </div>

        <!-- Recherche -->
        <form method="GET" action="{{ route('carburants.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Rechercher par station, camion..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-center">
                        <th class="px-4 py-2">Camion</th>
                        <th class="px-4 py-2">Trajet</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Quantité (L)</th>
                        <th class="px-4 py-2">Prix Total</th>
                        <th class="px-4 py-2">Station</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($carburants as $carburant)
                        <tr class="border-b hover:bg-gray-50 text-center">
                            <td class="px-4 py-2 font-medium">
                                {{ $carburant->camion->immatriculation ?? '—' }}
                            </td>
                                <td class="px-4 py-2 text-left">
                                @if ($carburant->trajet && $marchandise->trajet->itineraire)
                                    {{ $carburant->trajet->itineraire->lieu_depart }} →
                                    {{ $carburant->trajet->itineraire->lieu_arrivee }}
                                    ({{ \Carbon\Carbon::parse($carburant->trajet->date_depart)->format('d/m/Y') }})
                                @else
                                    -
                                @endif
                            </td>

                            <td class="px-4 py-2">{{ $carburant->date->format('d/m/Y') }}</td>
                            <td class="px-4 py-2">{{ $carburant->quantite_litres }} L</td>
                            <td class="px-4 py-2">{{ number_format($carburant->prix_total, 0, ',', ' ') }} Ar</td>
                            <td class="px-4 py-2">{{ $carburant->station ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('carburants.edit', $carburant) }}"
                                       class="text-blue-600 hover:text-blue-800 transition" title="Modifier">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('carburants.destroy', $carburant) }}" method="POST"
                                          onsubmit="return confirm('Supprimer cette entrée de carburant ?')">
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
                            <td colspan="8" class="text-center py-6 text-gray-500">Aucun enregistrement de carburant.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $carburants->links() }}
        </div>
    </div>
</x-layouts.app>
