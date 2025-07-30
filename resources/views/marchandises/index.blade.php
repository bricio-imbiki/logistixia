<x-layouts.app>
<div class="max-w-5xl mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Marchandises</h1>

    <a href="{{ route('marchandises.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded mb-4 inline-block">
        + Nouvelle Marchandise
    </a>

    <div class="bg-white rounded shadow overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2">Nom</th>
                    <th>Réf.</th>
                    <th>Catégorie</th>
                    <th>Poids</th>
                    <th>Unité</th>
                    <th>Tarif</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($marchandises as $m)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-4 py-2 font-medium">{{ $m->nom }}</td>
                        <td>{{ $m->reference }}</td>
                        <td>{{ $m->categorie }}</td>
                        <td>{{ $m->poids_moyen }}</td>
                        <td>{{ $m->unite }}</td>
                        <td>{{ number_format($m->tarif_par_defaut, 0, ',', ' ') }} Ar</td>
                        <td>
                            <a href="{{ route('marchandises.edit', $m) }}" class="text-blue-600 hover:underline">Modifier</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</x-layouts.app>
