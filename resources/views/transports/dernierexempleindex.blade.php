<x-layouts.app>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Liste des Transports</h1>

        <!-- Filters -->
        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher..." class="rounded border px-3 py-2">
            <select name="client_id" class="rounded border px-3 py-2">
                <option value="">Tous les clients</option>
                @foreach($clients as $client)
                    <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>{{ $client->raison_sociale }}</option>
                @endforeach
            </select>
            <select name="statut" class="rounded border px-3 py-2">
                <option value="">Tous les statuts</option>
                <option value="chargee" @selected(request('statut') == 'chargee')>Chargée</option>
                <option value="en_transit" @selected(request('statut') == 'en_transit')>En transit</option>
                <option value="livree" @selected(request('statut') == 'livree')>Livrée</option>
                <option value="retour" @selected(request('statut') == 'retour')>Retour</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Filtrer</button>
        </form>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-100 p-4 rounded text-center">
                <p class="font-bold">{{ $stats['chargee'] }}</p>
                <p>Chargée</p>
            </div>
            <div class="bg-green-100 p-4 rounded text-center">
                <p class="font-bold">{{ $stats['en_transit'] }}</p>
                <p>En transit</p>
            </div>
            <div class="bg-yellow-100 p-4 rounded text-center">
                <p class="font-bold">{{ $stats['livree'] }}</p>
                <p>Livrée</p>
            </div>
            <div class="bg-red-100 p-4 rounded text-center">
                <p class="font-bold">{{ $stats['retour'] }}</p>
                <p>Retour</p>
            </div>
        </div>

        <!-- Table -->
        <table class="w-full border-collapse bg-white rounded-lg shadow">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-3 text-left">Client</th>
                    <th class="p-3 text-left">Trajet</th>
                    <th class="p-3 text-left">Marchandise</th>
                    <th class="p-3 text-left">Quantité</th>
                    <th class="p-3 text-left">Poids (kg)</th>
                    <th class="p-3 text-left">Statut</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transports as $transport)
                    <tr class="border-t">
                        <td class="p-3">{{ $transport->client->raison_sociale }}</td>
                        <td class="p-3">
                            {{ $transport->trajet->itineraire->lieu_depart }} → {{ $transport->trajet->itineraire->lieu_arrivee }}
                            ({{ \Carbon\Carbon::parse($transport->trajet->date_depart)->format('d/m/Y') }})
                        </td>
                        <td class="p-3">{{ $transport->marchandise->nom }}</td>
                        <td class="p-3">{{ $transport->quantite }} {{ $transport->marchandise->unite }}</td>
                        <td class="p-3">{{ $transport->poids_kg }}</td>
                        <td class="p-3">{{ ucfirst($transport->statut) }}</td>
                        <td class="p-3 flex gap-2">
                            <a href="{{ route('transports.edit', $transport) }}" class="text-blue-600 hover:underline">Modifier</a>
                            <form action="{{ route('transports.destroy', $transport) }}" method="POST" onsubmit="return confirm('Confirmer la suppression ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $transports->links() }}
        </div>

        <a href="{{ route('transports.create') }}" class="mt-6 inline-block bg-blue-600 text-white px-4 py-2 rounded">
            Ajouter un transport
        </a>
    </div>
</x-layouts.app>
