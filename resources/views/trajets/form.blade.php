<x-layouts.app>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-md">

        <h1 class="text-2xl font-bold mb-6">
            {{ isset($trajet) ? "Modifier le trajet" : "Ajouter un nouveau trajet" }}
        </h1>

        <form method="POST"
              action="{{ isset($trajet) ? route('trajets.update', $trajet) : route('trajets.store') }}"
              novalidate>
            @csrf
            @if(isset($trajet))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Camion -->
                <div>
                    <label for="camion_id" class="block text-sm font-medium text-gray-700">Camion *</label>
                    <select name="camion_id" id="camion_id" required
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="">-- Sélectionner un camion --</option>
                        @foreach ($camions as $camion)
                            <option value="{{ $camion->id }}"
                                @selected(old('camion_id', $trajet->camion_id ?? '') == $camion->id)>
                                {{ $camion->matricule }} - {{ $camion->marque ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('camion_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Remorque -->
                <div>
                    <label for="remorque_id" class="block text-sm font-medium text-gray-700">Remorque</label>
                    <select name="remorque_id" id="remorque_id"
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="">-- Sélectionner une remorque --</option>
                        @foreach ($remorques as $remorque)
                            <option value="{{ $remorque->id }}"
                                @selected(old('remorque_id', $trajet->remorque_id ?? '') == $remorque->id)>
                                {{ $remorque->matricule }} - {{ $remorque->type ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('remorque_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Chauffeur -->
                <div>
                    <label for="chauffeur_id" class="block text-sm font-medium text-gray-700">Chauffeur</label>
                    <select name="chauffeur_id" id="chauffeur_id"
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="">-- Sélectionner un chauffeur --</option>
                        @foreach ($chauffeurs as $chauffeur)
                            <option value="{{ $chauffeur->id }}"
                                @selected(old('chauffeur_id', $trajet->chauffeur_id ?? '') == $chauffeur->id)>
                                {{ $chauffeur->nom }} {{ $chauffeur->prenom ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('chauffeur_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Itinéraire -->
                <div>
                    <label for="itineraire_id" class="block text-sm font-medium text-gray-700">Itinéraire</label>
                    <select name="itineraire_id" id="itineraire_id"
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="">-- Sélectionner un itinéraire --</option>
                        @foreach ($itineraires as $itineraire)
                            <option value="{{ $itineraire->id }}"
                                @selected(old('itineraire_id', $trajet->itineraire_id ?? '') == $itineraire->id)>
                                {{ $itineraire->lieu_depart }} → {{ $itineraire->lieu_arrivee }}
                            </option>
                        @endforeach
                    </select>
                    @error('itineraire_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Date départ -->
                <div>
                    <label for="date_depart" class="block text-sm font-medium text-gray-700">Date départ</label>
                    <input type="datetime-local" name="date_depart" id="date_depart"
                        value="{{ old('date_depart', isset($trajet->date_depart) ? \Carbon\Carbon::parse($trajet->date_depart)->format('Y-m-d\TH:i') : '') }}"
                        class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
                    @error('date_depart') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Date arrivée ETD -->
                <div>
                    <label for="date_arrivee_etd" class="block text-sm font-medium text-gray-700">Date arrivée ETD</label>
                    <input type="datetime-local" name="date_arrivee_etd" id="date_arrivee_etd"
                        value="{{ old('date_arrivee_etd', isset($trajet->date_arrivee_etd) ? \Carbon\Carbon::parse($trajet->date_arrivee_etd)->format('Y-m-d\TH:i') : '') }}"
                        class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
                    @error('date_arrivee_etd') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Date arrivée ETA -->
                <div>
                    <label for="date_arrivee_eta" class="block text-sm font-medium text-gray-700">Date arrivée ETA</label>
                    <input type="datetime-local" name="date_arrivee_eta" id="date_arrivee_eta"
                        value="{{ old('date_arrivee_eta', isset($trajet->date_arrivee_eta) ? \Carbon\Carbon::parse($trajet->date_arrivee_eta)->format('Y-m-d\TH:i') : '') }}"
                        class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" />
                    @error('date_arrivee_eta') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" id="statut" required
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="prevu" @selected(old('statut', $trajet->statut ?? '') == 'prevu')>Prévu</option>
                        <option value="en_cours" @selected(old('statut', $trajet->statut ?? '') == 'en_cours')>En cours</option>
                        <option value="termine" @selected(old('statut', $trajet->statut ?? '') == 'termine')>Terminé</option>
                        <option value="annule" @selected(old('statut', $trajet->statut ?? '') == 'annule')>Annulé</option>
                    </select>
                    @error('statut') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Commentaire -->
                <div class="md:col-span-2">
                    <label for="commentaire" class="block text-sm font-medium text-gray-700">Commentaire</label>
                    <textarea name="commentaire" id="commentaire" rows="3"
                              class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 resize-y">{{ old('commentaire', $trajet->commentaire ?? '') }}</textarea>
                    @error('commentaire') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

            </div>

            <!-- Boutons -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('trajets.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>

                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($trajet))
                        <i class="fas fa-pen mr-1"></i> Mettre à jour
                    @else
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    @endif
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
