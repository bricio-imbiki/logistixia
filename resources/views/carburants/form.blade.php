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
                {{-- Camion --}}
                <x-forms.select
                    name="camion_id"
                    label="Camion concerné"
                    :options="$camions->pluck('matricule', 'id')->toArray()"
                    :selected="old('camion_id', $carburant->camion_id ?? '')"
                    required
                />

                {{-- Trajet (optionnel) --}}
                <div>
                    <label for="trajet_id" class="block text-sm font-medium text-gray-700 mb-1">Trajet associé (facultatif)</label>
                    <select name="trajet_id" id="trajet_id"
                            class="block w-full border-gray-300 rounded px-3 py-2 shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Aucun --</option>
                        @foreach($trajets as $trajet)
                            <option value="{{ $trajet->id }}"
                                {{ old('trajet_id', $carburant->trajet_id ?? '') == $trajet->id ? 'selected' : '' }}>
                                {{ $trajet->itineraire->lieu_depart }} → {{ $trajet->itineraire->lieu_arrivee }} ({{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('trajet_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Date --}}
                <x-forms.input
                    name="date_achat"
                    label="Date du plein"
                    type="date"
                  :value="old('date_achat', $carburant->date_achat ?? now()->toDateString())"

                    required
                />

                {{-- Quantité --}}
                <x-forms.input
                    name="quantite_litres"
                    label="Quantité (en litres)"
                    type="number"
                    step="0.01"
                    :value="old('quantite_litres', $carburant->quantite_litres ?? '')"
                    required
                />
<x-forms.input
    name="prix_unitaire"
    label="Prix unitaire (Ar/L)"
    type="number"
    step="1"
    :value="old('prix_unitaire', $carburant->prix_unitaire ?? '')"
    required
/>

                {{-- Prix total --}}
                <x-forms.input
                    name="prix_total"
                    label="Prix total (en Ar)"
                    type="number"
                    step="100"
                    :value="old('prix_total', $carburant->prix_total ?? '')"
                    required
                />

                {{-- Station --}}
                <x-forms.input
                    name="station"
                    label="Station essence"
                    :value="old('station', $carburant->station ?? '')"
                    required
                />
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
