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
                <x-heroicon-o-truck class="w-6 h-6 text-blue-600" />
                <span>Gestion des trajets</span>
            </h2>

            <a href="{{ route('trajets.create') }}"
               class=" no-underline inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Ajouter
            </a>
        </div>

        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('trajets.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Rechercher par matricule camion..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                        <th class="px-4 py-2">Camion</th>
                        <th class="px-4 py-2">Remorque</th>
                        <th class="px-4 py-2">Chauffeur</th>
                        <th class="px-4 py-2">Itinéraire</th>
                        <th class="px-4 py-2">Date départ</th>
                        <th class="px-4 py-2">Date arrivée ETD</th>
                        <th class="px-4 py-2">Date arrivée ETA</th>
                        <th class="px-4 py-2">Statut</th>
                        <th class="px-4 py-2">Commentaire</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($trajets as $trajet)
                        <tr class="border-b hover:bg-gray-50 text-center">
                            <td class="px-4 py-2 font-medium">
                                {{ $trajet->camion ? $trajet->camion->matricule : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $trajet->remorque ? $trajet->remorque->matricule : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $trajet->chauffeur ? $trajet->chauffeur->nom : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $trajet->itineraire ? $trajet->itineraire->lieu_depart . ' → ' . $trajet->itineraire->lieu_arrivee : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $trajet->date_depart ? \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $trajet->date_arrivee_etd ? \Carbon\Carbon::parse($trajet->date_arrivee_etd)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                {{ $trajet->date_arrivee_eta ? \Carbon\Carbon::parse($trajet->date_arrivee_eta)->format('d/m/Y H:i') : '-' }}
                            </td>
                            <td class="px-4 py-2">
                                @php
                                    $statut = strtolower(trim($trajet->statut));
                                @endphp
                                <span @class([
                                    'bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold' => $statut === 'prevu',
                                    'bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold' => $statut === 'en_cours',
                                    'bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold' => $statut === 'termine',
                                    'bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold' => $statut === 'annule',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $trajet->statut)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2 max-w-xs truncate" title="{{ $trajet->commentaire }}">
                                {{ $trajet->commentaire ?? '-' }}
                            </td>
                            <td class="px-4 py-2 text-center align-middle">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <a href="{{ route('trajets.edit', $trajet->id) }}"
                                       class="text-blue-600 hover:text-blue-800 transition" title="Modifier">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('trajets.destroy', $trajet->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer ce trajet ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition" title="Supprimer">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10" class="text-center py-6 text-gray-500">Aucun trajet trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $trajets->links() }}
        </div>

    </div>
</x-layouts.app>
