<!-- resources/views/modals/client-create.blade.php -->
<div id="clientModal"
     class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50 opacity-0 invisible transition-opacity duration-300 ease-in-out"
     aria-modal="true" role="dialog" aria-labelledby="clientModalTitle">

    <div id="clientModalContent"
         class="bg-white p-6 rounded-xl w-full max-w-3xl mx-4 max-h-screen overflow-y-auto transform -translate-y-full scale-95 transition-all duration-300 ease-in-out shadow-2xl">

        <!-- En-tête -->
        <div class="flex justify-between items-center mb-6">
            <h3 id="clientModalTitle" class="text-2xl font-bold text-gray-800">Ajouter un nouveau client</h3>
            <button type="button" onclick="closeClientModal()"
                    class="text-gray-400 hover:text-gray-600 hover:bg-gray-100 w-8 h-8 rounded-full flex items-center justify-center transition-all duration-200"
                    aria-label="Fermer la modale">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Formulaire -->
        <form id="clientForm" novalidate>
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Raison sociale -->
                <div>
                    <label for="modal_raison_sociale" class="block text-sm font-medium text-gray-700 mb-1">Raison sociale *</label>
                    <input type="text" name="raison_sociale" id="modal_raison_sociale" required
                           placeholder="Ex: Société XYZ"
                           class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                    <p id="modal_raison_sociale_error" class="text-red-600 text-xs mt-1 hidden"></p>
                </div>

                <!-- Nom -->
                <div>
                    <label for="modal_client" class="block text-sm font-medium text-gray-700 mb-1">Nom du client</label>
                    <input type="text" name="client" id="modal_client" placeholder="Jean Dupont"
                           class="block w-full rounded-lg border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
                </div>

                <!-- Téléphone -->
                <div>
                    <label for="modal_telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                    <input type="text" name="telephone" id="modal_telephone"
                           class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Email -->
                <div>
                    <label for="modal_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="modal_email"
                           class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Adresse -->
                <div class="md:col-span-1">
                    <label for="modal_adresse" class="block text-sm font-medium text-gray-700 mb-1">Adresse</label>
                    <input type="text" name="adresse" id="modal_adresse"
                           class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Type de client -->
                <div class="md:col-span-1">
                    <label for="modal_type_client" class="block text-sm font-medium text-gray-700 mb-1">Type de client</label>
                    <select name="type_client" id="modal_type_client"
                            class="block w-full rounded border border-gray-300 px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Sélectionner un type --</option>
                        @foreach(\App\Enums\ClientType::cases() as $type)
                            <option value="{{ $type->value }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Boutons -->
            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeClientModal()"
                        class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded border border-gray-300">
                    <i class="fas fa-times mr-1"></i> Annuler
                </button>
                <button type="submit"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 flex items-center gap-2">
                    <i class="fas fa-save"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
