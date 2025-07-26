<x-layouts.app>
    <!-- Message flash -->
    @if (session('message'))
        <div class="mb-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow">
            {{ session('message') }}
        </div>
    @endif
    <div class="p-6 bg-white rounded-xl shadow-md">

        <!-- Titre + Bouton Ajouter -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-3 mb-4">
                <i
                    class="fas fa-truck-moving text-white bg-blue-600 p-2 rounded-full w-8 h-8 flex items-center justify-center"></i>
                <span>Gestion des remorques</span>
            </h2>


            <a href="{{ route('remorques.create') }}"
                class=" no-underline inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Ajouter
            </a>
        </div>



        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('remorques.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="🔍 Rechercher par matricule, type..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                        <th class="px-4 py-2">Photo</th>
                        <th class="px-4 py-2">Matricule</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Capacité (T)</th>
                        <th class="px-4 py-2">Interne</th>
                        <th class="px-4 py-2">Camion</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($remorques as $remorque)
                       <tr class="border-b hover:bg-gray-50 text-center">
                            <td class="px-4 py-2">
                                @if ($remorque->photo_url)
                                    <img src="{{ Storage::url($remorque->photo_url) }}"
                                        class="h-12 w-16 object-cover rounded shadow">
                                @else
                                    <span class="text-xs text-gray-400 italic">Aucune</span>
                                @endif
                            </td>
                            <td class="px-4 py-2 font-medium">{{ $remorque->matricule }}</td>
                            <td class="px-4 py-2">{{ $remorque->type ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $remorque->capacite_max ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span @class([
                                    'bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold' =>
                                        $remorque->est_interne,
                                    'bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold' => !$remorque->est_interne,
                                ])>
                                    {{ $remorque->est_interne ? 'Oui' : 'Non' }}
                                </span>
                            </td>
                            <td class="px-4 py-2">{{ $remorque->camion?->matricule ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('remorques.edit', $remorque->id) }}"
                                        class="text-blue-600 hover:text-blue-800 transition" title="Modifier">
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </a>
                                    <form action="{{ route('remorques.destroy', $remorque->id) }}" method="POST"
                                        onsubmit="return confirm('Supprimer cette remorque ?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 transition"
                                            title="Supprimer">
                                            <x-heroicon-o-trash class="w-5 h-5" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">Aucune remorque trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $remorques->links() }}
        </div>
    </div>
</x-layouts.app>
