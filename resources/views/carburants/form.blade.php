<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($carburant) ? 'Modifier le carburant' : 'Ajouter un nouveau plein' }}
        </h1>

        <form method="POST"
              action="{{ isset($carburant) ? route('carburants.update', $carburant) : route('carburants.store') }}"
              novalidate>
            @csrf
            @if(isset($carburant))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <x-forms.select name="vehicule_id" label="Camion concerné"
                    :options="$camions->pluck('immatriculation', 'id')->toArray()"
                    :selected="old('vehicule_id', $carburant->vehicule_id ?? '')"
                    required />

                <x-forms.select name="trajet_id" label="Trajet associé (facultatif)"
                    :options="$trajets->pluck('reference', 'id')->toArray()"
                    :selected="old('trajet_id', $carburant->trajet_id ?? '')" />

                <x-forms.input name="date" label="Date du plein" type="date"
                    :value="old('date', $carburant->date ?? now()->toDateString())" required />

                <x-forms.input name="quantite_litres" label="Quantité (en litres)" type="number" step="0.01"
                    :value="old('quantite_litres', $carburant->quantite_litres ?? '')" required />

                <x-forms.input name="prix_total" label="Prix total (en Ar)" type="number" step="100"
                    :value="old('prix_total', $carburant->prix_total ?? '')" required />

                <x-forms.input name="station" label="Station essence"
                    :value="old('station', $carburant->station ?? '')" required />

                <x-forms.select name="type" label="Type de carburant"
                    :options="['gasoil' => 'Gasoil', 'essence' => 'Essence']"
                    :selected="old('type', $carburant->type ?? 'gasoil')" required />
            </div>

            {{-- Boutons --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('carburants.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>

                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($carburant))
                        <i class="fas fa-pen mr-1"></i> Mettre à jour
                    @else
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    @endif
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
