<!-- resources/views/modals/trajet-create.blade.php -->
<div id="trajetModal"
     class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 opacity-0 invisible transition-opacity duration-300 ease-in-out"
     aria-modal="true" role="dialog" aria-labelledby="trajetModalTitle">

    <div id="trajetModalContent"
         class="bg-white p-6 rounded-xl w-full max-w-4xl mx-4 max-h-screen overflow-y-auto transform -translate-y-full scale-95 transition-all duration-300 ease-in-out shadow-2xl relative">

        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <h3 id="trajetModalTitle" class="text-2xl font-bold text-gray-800">Ajouter un nouveau trajet</h3>
            <button type="button" onclick="closeTrajetModal()"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200"
                    aria-label="Fermer la modale">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form id="trajetForm"novalidate>
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Camion -->
                <div>
                    <label for="modal_camion_id" class="block text-sm font-medium text-gray-700 mb-1">Camion *</label>
                    <select name="camion_id" id="modal_camion_id" required
                            class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Sélectionner un camion --</option>
                        @foreach ($camions ?? [] as $camion)
                            <option value="{{ $camion->id }}">{{ $camion->matricule }} - {{ $camion->marque ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Remorque -->
                <div>
                    <label for="modal_remorque_id" class="block text-sm font-medium text-gray-700 mb-1">Remorque</label>
                    <select name="remorque_id" id="modal_remorque_id"
                            class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Sélectionner une remorque --</option>
                        @foreach ($remorques ?? [] as $remorque)
                            <option value="{{ $remorque->id }}">{{ $remorque->matricule }} - {{ $remorque->type ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Chauffeur -->
                <div>
                    <label for="modal_chauffeur_id" class="block text-sm font-medium text-gray-700 mb-1">Chauffeur</label>
                    <select name="chauffeur_id" id="modal_chauffeur_id"
                            class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Sélectionner un chauffeur --</option>
                        @foreach ($chauffeurs ?? [] as $chauffeur)
                            <option value="{{ $chauffeur->id }}">{{ $chauffeur->nom }} {{ $chauffeur->prenom ?? '' }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Itinéraire -->
                <div>
                    <label for="modal_itineraire_id" class="block text-sm font-medium text-gray-700 mb-1">Itinéraire *</label>
                    <select name="itineraire_id" id="modal_itineraire_id" required
                            class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="">-- Sélectionner un itinéraire --</option>
                        @foreach ($itineraires ?? [] as $itineraire)
                            <option value="{{ $itineraire->id }}">{{ $itineraire->lieu_depart }} → {{ $itineraire->lieu_arrivee }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date départ -->
                <div>
                    <label for="modal_date_depart" class="block text-sm font-medium text-gray-700 mb-1">Date départ *</label>
                    <input type="datetime-local" name="date_depart" id="modal_date_depart" required
                           class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Date arrivée ETD -->
                <div>
                    <label for="modal_date_arrivee_etd" class="block text-sm font-medium text-gray-700 mb-1">Date arrivée ETD</label>
                    <input type="datetime-local" name="date_arrivee_etd" id="modal_date_arrivee_etd"
                           class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Date arrivée ETA -->
                <div>
                    <label for="modal_date_arrivee_eta" class="block text-sm font-medium text-gray-700 mb-1">Date arrivée ETA</label>
                    <input type="datetime-local" name="date_arrivee_eta" id="modal_date_arrivee_eta"
                           class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                </div>

                <!-- Statut -->
                <div>
                    <label for="modal_statut" class="block text-sm font-medium text-gray-700 mb-1">Statut *</label>
                    <select name="statut" id="modal_statut" required
                            class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <option value="prevu" selected>Prévu</option>
                        <option value="en_cours">En cours</option>
                        <option value="termine">Terminé</option>
                        <option value="annule">Annulé</option>
                    </select>
                </div>

                <!-- Commentaire -->
                <div class="md:col-span-2">
                    <label for="modal_commentaire" class="block text-sm font-medium text-gray-700 mb-1">Commentaire</label>
                    <textarea name="commentaire" id="modal_commentaire" rows="3"
                              placeholder="Commentaire optionnel sur le trajet"
                              class="block w-full rounded border border-gray-300 px-3 py-2 resize-y focus:ring-2 focus:ring-green-500 focus:border-green-500"></textarea>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeTrajetModal()"
                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded border border-gray-300">
                    <i class="fas fa-times mr-1"></i> Annuler
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 flex items-center gap-2">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
