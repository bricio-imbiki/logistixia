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
                <x-heroicon-o-user-group class="w-6 h-6 text-blue-600" />
                <span>Gestion des chauffeurs</span>
            </h2>

            <a href="{{ route('chauffeurs.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Ajouter
            </a>
        </div>

        <!-- message  -->
        @if (session('message'))
            <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow">
                {{ session('message') }}
            </div>
        @endif

        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('chauffeurs.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Rechercher par nom, téléphone..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                        <th class="px-4 py-2">Photo</th>
                        <th class="px-4 py-2">Nom</th>
                        <th class="px-4 py-2">Téléphone</th>
                        <th class="px-4 py-2">Permis</th>
                        <th class="px-4 py-2">Statut</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($chauffeurs as $chauffeur)
                     <tr class="border-b hover:bg-gray-50 text-center">
                            <td class="px-4 py-2">
                                @if ($chauffeur->photo_url)
                                    <img src="{{ Storage::url($chauffeur->photo_url) }}" class="h-8 w-8 object-cover rounded-full shadow">
                                @else
                                    <span class="text-xs text-gray-400 italic">Aucune</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 font-medium">
                                {{ $chauffeur->nom }} {{ $chauffeur->prenom }}
                            </td>
                            <td class="px-4 py-2">{{ $chauffeur->telephone ?? '-' }}</td>
                            <td class="px-4 py-2">
                                {{ $chauffeur->permis_num }}
                                <span class="text-xs text-gray-500">({{ $chauffeur->permis_categorie }})</span>
                            </td>
                            <td class="px-4 py-2">
                                <span @class([
                                    'bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold' => $chauffeur->statut === 'titulaire',
                                    'bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold' => $chauffeur->statut === 'remplacant',
                                ])>
                                    {{ ucfirst($chauffeur->statut) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('chauffeurs.edit', $chauffeur->id) }}"
                                       class="text-blue-600 hover:text-blue-800 transition" title="Modifier">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('chauffeurs.destroy', $chauffeur->id) }}" method="POST"
                                          onsubmit="return confirm('Supprimer ce chauffeur ?')">
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
                            <td colspan="6" class="text-center py-6 text-gray-500">Aucun chauffeur trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $chauffeurs->links() }}
        </div>
    </div>
</x-layouts.app>
