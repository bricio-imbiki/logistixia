<x-layouts.app>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-md">

        <h1 class="text-2xl font-bold mb-6">
            {{ isset($itineraire) ? "Modifier l'itinéraire" : "Ajouter un nouvel itinéraire" }}
        </h1>

        <form method="POST" action="{{ isset($itineraire) ? route('itineraires.update', $itineraire) : route('itineraires.store') }}" novalidate>
            @csrf
            @if(isset($itineraire))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label for="lieu_depart" class="block text-sm font-medium text-gray-700">Lieu départ</label>
                    <input type="text" name="lieu_depart" id="lieu_depart"
                           value="{{ old('lieu_depart', $itineraire->lieu_depart ?? '') }}"
                           placeholder="Entrez le lieu de départ"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
                    @error('lieu_depart') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="lieu_arrivee" class="block text-sm font-medium text-gray-700">Lieu arrivée</label>
                    <input type="text" name="lieu_arrivee" id="lieu_arrivee"
                           value="{{ old('lieu_arrivee', $itineraire->lieu_arrivee ?? '') }}"
                           placeholder="Entrez le lieu d'arrivée"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
                    @error('lieu_arrivee') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="distance_km" class="block text-sm font-medium text-gray-700">Distance (km)</label>
                    <input type="number" step="0.01" min="0" name="distance_km" id="distance_km"
                           value="{{ old('distance_km', $itineraire->distance_km ?? '') }}"
                           placeholder="Distance en kilomètres"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
                    @error('distance_km') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="duree_estimee_h" class="block text-sm font-medium text-gray-700">Durée estimée (h)</label>
                    <input type="number" step="0.01" min="0" name="duree_estimee_h" id="duree_estimee_h"
                           value="{{ old('duree_estimee_h', $itineraire->duree_estimee_h ?? '') }}"
                           placeholder="Durée estimée en heures"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
                    @error('duree_estimee_h') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="peage_estime" class="block text-sm font-medium text-gray-700">Péage estimé</label>
                    <input type="number" step="0.01" min="0" name="peage_estime" id="peage_estime"
                           value="{{ old('peage_estime', $itineraire->peage_estime ?? '') }}"
                           placeholder="Montant estimé du péage"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
                    @error('peage_estime') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('itineraires.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>

                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($itineraire))
                        <i class="fas fa-pen mr-1"></i> Mettre à jour
                    @else
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    @endif
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
