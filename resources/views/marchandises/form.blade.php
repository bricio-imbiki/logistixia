<x-layouts.app>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-md">

        <h1 class="text-2xl font-bold mb-6">
            {{ isset($marchandise) ? "Modifier la marchandise" : "Ajouter une nouvelle marchandise" }}
        </h1>

        <form method="POST"
              action="{{ isset($marchandise) ? route('marchandises.update', $marchandise) : route('marchandises.store') }}"
              novalidate>
            @csrf
            @if(isset($marchandise))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Client -->
                <div>
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client *</label>
                    <select name="client_id" id="client_id" required
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="">-- Sélectionner un client --</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}"
                                @selected(old('client_id', $marchandise?->client_id ?? '') == $client->id)>
                                {{ $client->raison_sociale }}
                            </option>
                        @endforeach
                    </select>
                    @error('client_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>


                <!-- Trajet -->
                <div>
                    <label for="trajet_id" class="block text-sm font-medium text-gray-700">Trajet *</label>
                    <select name="trajet_id" id="trajet_id" required
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="">-- Sélectionner un trajet --</option>
                        @foreach($trajets as $trajet)
                            <option value="{{ $trajet->id }}"
                                    @selected(old('trajet_id', $marchandise?->trajet_id ?? '') == $trajet->id)>
                                {{ $trajet->itineraire->lieu_depart }} → {{ $trajet->itineraire->lieu_arrivee }}
                                ({{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }})
                            </option>
                        @endforeach
                    </select>
                    @error('trajet_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                              class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 resize-y">{{ old('description', $marchandise?->description ?? '') }}</textarea>
                    @error('description') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Poids (kg) -->
                <div>
                    <label for="poids_kg" class="block text-sm font-medium text-gray-700">Poids (kg)</label>
                    <input type="number" step="0.01" name="poids_kg" id="poids_kg"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2"
                           value="{{ old('poids_kg', $marchandise?->poids_kg ?? '') }}">
                    @error('poids_kg') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Volume (m³) -->
                <div>
                    <label for="volume_m3" class="block text-sm font-medium text-gray-700">Volume (m³)</label>
                    <input type="number" step="0.01" name="volume_m3" id="volume_m3"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2"
                           value="{{ old('volume_m3', $marchandise?->volume_m3 ?? '') }}">
                    @error('volume_m3') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Valeur estimée (Ariary) -->
                <div>
                    <label for="valeur_estimee" class="block text-sm font-medium text-gray-700">Valeur estimée (Ariary)</label>
                    <input type="number" step="0.01" name="valeur_estimee" id="valeur_estimee"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2"
                           value="{{ old('valeur_estimee', $marchandise?->valeur_estimee ?? '') }}">
                    @error('valeur_estimee') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Lieu de livraison -->
                <div>
                    <label for="lieu_livraison" class="block text-sm font-medium text-gray-700">Lieu de livraison</label>
                    <input type="text" name="lieu_livraison" id="lieu_livraison"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2"
                           value="{{ old('lieu_livraison', $marchandise?->lieu_livraison ?? '') }}">
                    @error('lieu_livraison') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" id="statut"
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="chargee" @selected(old('statut', $marchandise?->statut ?? '') == 'chargee')>Chargée</option>
                        <option value="en_transit" @selected(old('statut', $marchandise?->statut ?? '') == 'en_transit')>En transit</option>
                        <option value="livree" @selected(old('statut', $marchandise?->statut ?? '') == 'livree')>Livrée</option>
                        <option value="retour" @selected(old('statut', $marchandise?->statut ?? '') == 'retour')>Retour</option>
                    </select>
                    @error('statut') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            <!-- Boutons -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('marchandises.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>

                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($marchandise))
                         <i class="fas fa-pen mr-1"></i> Mettre à jour
                    @else
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    @endif
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
