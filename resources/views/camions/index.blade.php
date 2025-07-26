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
                <span>Gestion des camions</span>
            </h2>

            <a href="{{ route('camions.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Ajouter
            </a>
        </div>


        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('camions.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Rechercher par matricule, marque..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700">
               <thead>
    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
        <th class="px-4 py-2">Photo</th>
        <th class="px-4 py-2">Matricule</th>
        <th class="px-4 py-2">Marque</th>
        <th class="px-4 py-2">Modèle</th>
        <th class="px-4 py-2">Année</th>
        <th class="px-4 py-2">Capacité</th>
        <th class="px-4 py-2">Statut</th>
        <th class="px-4 py-2">Type</th>
        <th class="px-4 py-2">Propriétaire</th>
        <th class="px-4 py-2 text-center">Actions</th>
    </tr>
</thead>

               <tbody>
    @forelse ($camions as $camion)
         <tr class="border-b hover:bg-gray-50 text-center">
            <td class="px-4 py-2">
                @if ($camion->photo_url)
                    <img src="{{ Storage::url($camion->photo_url) }}" class="h-12 w-12 object-cover rounded shadow">
                @else
                    <span class="text-xs text-gray-400 italic">Aucune</span>
                @endif
            </td>
            <td class="px-4 py-2 font-medium">{{ $camion->matricule }}</td>
            <td class="px-4 py-2">{{ $camion->marque }}</td>
            <td class="px-4 py-2">{{ $camion->modele ?? '-' }}</td>
            <td class="px-4 py-2">{{ $camion->annee ?? '-' }}</td>
            <td class="px-4 py-2">{{ number_format($camion->capacite_kg, 0, ',', ' ') }} kg</td>
            <td class="px-4 py-2">
 @php
    $statut = strtolower(trim($camion->statut));
@endphp

<span @class([
    'bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold' => $statut === 'disponible',
    'bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold' => $statut === 'en_mission',
    'bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold' => $statut === 'panne',
    'bg-gray-200 text-gray-800 px-2 py-1 rounded text-xs font-semibold' => $statut === 'maintenance',
])>
    {{ ucfirst(str_replace('_', ' ', $camion->statut)) }}
</span>


            </td>
            <td class="px-4 py-2">
                <span class="text-sm">
                    {{ $camion->est_interne ? 'Interne' : 'Externe' }}
                </span>
            </td>
            <td class="px-4 py-2">{{ $camion->societe_proprietaire ?? '-' }}</td>
           <td class="px-4 py-2 text-center align-middle">
    <div class="inline-flex items-center justify-center gap-2">
        <a href="{{ route('camions.edit', $camion->id) }}"
           class="text-blue-600 hover:text-blue-800 transition" title="Modifier">
            <x-heroicon-o-pencil class="w-5 h-5" />
        </a>
        <form action="{{ route('camions.destroy', $camion->id) }}" method="POST"
              onsubmit="return confirm('Supprimer ce camion ?')">
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
            <td colspan="10" class="text-center py-6 text-gray-500">Aucun camion trouvé.</td>
        </tr>
    @endforelse
</tbody>

            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $camions->links() }}
        </div>

    </div>
</x-layouts.app>
