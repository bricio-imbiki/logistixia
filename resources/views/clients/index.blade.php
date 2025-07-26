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
                <span>Gestion des clients</span>
            </h2>

            <a href="{{ route('clients.create') }}"
               class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Ajouter
            </a>
        </div>

        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('clients.index') }}" class="mb-4">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Rechercher par raison sociale, contact, email..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none">
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                        <th class="px-4 py-2">Raison sociale</th>
                        <th class="px-4 py-2">Contact</th>
                        <th class="px-4 py-2">Téléphone</th>
                        <th class="px-4 py-2">Email</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clients as $client)
                       <tr class="border-b hover:bg-gray-50 text-center">
                            <td class="px-4 py-2 font-medium">{{ $client->raison_sociale }}</td>
                            <td class="px-4 py-2">{{ $client->contact ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $client->telephone ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $client->email ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span @class([
                                    'bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold' => $client->type_client === 'industriel',
                                    'bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold' => $client->type_client === 'commercial',
                                    'bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold' => $client->type_client === 'particulier',
                                ])>
                                    {{ ucfirst($client->type_client) }}
                                </span>
                            </td>
                           <td class="px-4 py-2">
    <div class="flex items-center justify-center gap-2">
        <a href="{{ route('clients.edit', $client->id) }}"
           class="text-blue-600 hover:text-blue-800 transition" title="Modifier">
            <x-heroicon-o-pencil class="w-5 h-5" />
        </a>
        <form action="{{ route('clients.destroy', $client->id) }}" method="POST"
              onsubmit="return confirm('Supprimer ce client ?')">
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
                            <td colspan="6" class="text-center py-6 text-gray-500">Aucun client trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $clients->links() }}
        </div>


    </div>
</x-layouts.app>
