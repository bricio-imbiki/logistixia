<!-- resources/views/marchandise/form.blade.php -->
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
                <div class="md:col-span-2">
                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client *</label>
                    <div class="flex gap-2">
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
                        <button type="button" onclick="openClientModal()"
                                class="mt-1 bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 whitespace-nowrap">
                            + Ajouter Client
                        </button>
                    </div>
                    @error('client_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Trajet -->
                <div class="md:col-span-2">
                    <label for="trajet_id" class="block text-sm font-medium text-gray-700">Trajet *</label>
                    <div class="flex gap-2">
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
                        <button type="button" onclick="openTrajetModal()"
                                class="mt-1 bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 whitespace-nowrap">
                            + Ajouter Trajet
                        </button>
                    </div>
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

    <!-- Modales -->
    @include('modals.client-create')
    @include('modals.trajet-create')

    <script>
        // === FONCTIONS POUR MODALE CLIENT ===
        function openClientModal() {
            const modal = document.getElementById('clientModal');
            const modalContent = document.getElementById('clientModalContent');

            // Afficher la modale avec l'overlay
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');

            // Animer le contenu du haut vers le centre
            setTimeout(() => {
                modalContent.classList.remove('-translate-y-full', 'scale-95');
                modalContent.classList.add('translate-y-0', 'scale-100');
            }, 10);

            // Focus sur le premier champ
            setTimeout(() => {
                document.getElementById('modal_raison_sociale')?.focus();
            }, 350);
        }

        function closeClientModal() {
            const modal = document.getElementById('clientModal');
            const modalContent = document.getElementById('clientModalContent');

            // Animer le contenu vers le haut
            modalContent.classList.remove('translate-y-0', 'scale-100');
            modalContent.classList.add('-translate-y-full', 'scale-95');

            // Masquer la modale après l'animation
            setTimeout(() => {
                modal.classList.remove('opacity-100', 'visible');
                modal.classList.add('opacity-0', 'invisible');
                document.getElementById('clientForm').reset();
            }, 300);
        }

        // === FONCTIONS POUR MODALE TRAJET ===
        function openTrajetModal() {
            const modal = document.getElementById('trajetModal');
            const modalContent = document.getElementById('trajetModalContent');

            // Afficher la modale avec l'overlay
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');

            // Animer le contenu du haut vers le centre
            setTimeout(() => {
                modalContent.classList.remove('-translate-y-full', 'scale-95');
                modalContent.classList.add('translate-y-0', 'scale-100');
            }, 10);

            // Focus sur le premier champ
            setTimeout(() => {
                document.getElementById('modal_camion_id')?.focus();
            }, 350);
        }

        function closeTrajetModal() {
            const modal = document.getElementById('trajetModal');
            const modalContent = document.getElementById('trajetModalContent');

            // Animer le contenu vers le haut
            modalContent.classList.remove('translate-y-0', 'scale-100');
            modalContent.classList.add('-translate-y-full', 'scale-95');

            // Masquer la modale après l'animation
            setTimeout(() => {
                modal.classList.remove('opacity-100', 'visible');
                modal.classList.add('opacity-0', 'invisible');
                document.getElementById('trajetForm').reset();
            }, 300);
        }

        // === SOUMISSION FORMULAIRE CLIENT ===
        document.getElementById('clientForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            // Désactiver le bouton de soumission
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Enregistrement...';

        fetch('{{ route('clients.store.ajax') }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': formData.get('_token'),
        'Accept': 'application/json'
    },
    body: formData
})
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Ajouter le nouveau client au select
                    const select = document.getElementById('client_id');
                    const option = new Option(data.client.raison_sociale, data.client.id, true, true);
                    select.appendChild(option);

                    // Fermer la modale
                    closeClientModal();

                    // Message de succès
                    showSuccessMessage('Client ajouté avec succès');
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'ajout du client');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorMessage('Erreur lors de l\'ajout du client: ' + error.message);
            })
            .finally(() => {
                // Réactiver le bouton
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // === SOUMISSION FORMULAIRE TRAJET ===
        document.getElementById('trajetForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Enregistrement...';

        fetch('{{ route('trajets.store.ajax') }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': formData.get('_token'),
        'Accept': 'application/json'
    },
    body: formData
})

            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Ajouter le nouveau trajet au select
                    const select = document.getElementById('trajet_id');
                    const optionText = `${data.trajet.lieu_depart} → ${data.trajet.lieu_arrivee} (${data.trajet.date_depart})`;
                    const option = new Option(optionText, data.trajet.id, true, true);
                    select.appendChild(option);

                    closeTrajetModal();
                    showSuccessMessage('Trajet ajouté avec succès');
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'ajout du trajet');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorMessage('Erreur lors de l\'ajout du trajet: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // === FONCTIONS UTILITAIRES ===
        function showSuccessMessage(message) {
            // Vous pouvez utiliser une librairie comme Toastr ou créer votre propre système de notification
            alert(message); // Remplacez par votre système de notification
        }

        function showErrorMessage(message) {
            alert(message); // Remplacez par votre système de notification
        }

        // Fermer les modales en cliquant à l'extérieur ou avec Escape
        document.addEventListener('click', function(e) {
            if (e.target.id === 'clientModal') {
                closeClientModal();
            }
            if (e.target.id === 'trajetModal') {
                closeTrajetModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const clientModal = document.getElementById('clientModal');
                const trajetModal = document.getElementById('trajetModal');

                if (clientModal.classList.contains('opacity-100')) {
                    closeClientModal();
                }
                if (trajetModal.classList.contains('opacity-100')) {
                    closeTrajetModal();
                }
            }
        });

        // Empêcher le scroll du body quand une modale est ouverte
        function toggleBodyScroll(disable) {
            if (disable) {
                document.body.style.overflow = 'hidden';
            } else {
                document.body.style.overflow = '';
            }
        }

        // Mettre à jour les fonctions d'ouverture pour désactiver le scroll
        const originalOpenClientModal = openClientModal;
        const originalOpenTrajetModal = openTrajetModal;
        const originalCloseClientModal = closeClientModal;
        const originalCloseTrajetModal = closeTrajetModal;

        openClientModal = function() {
            toggleBodyScroll(true);
            originalOpenClientModal();
        };

        openTrajetModal = function() {
            toggleBodyScroll(true);
            originalOpenTrajetModal();
        };

        closeClientModal = function() {
            toggleBodyScroll(false);
            originalCloseClientModal();
        };

        closeTrajetModal = function() {
            toggleBodyScroll(false);
            originalCloseTrajetModal();
        };
    </script>

</x-layouts.app>


{{--resources/views/transports/form.blade.php--}}
<x-layouts.app>
    <!-- Toastify pour notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($marchandise) ? "Modifier la marchandise" : "Ajouter des marchandises" }}
        </h1>

        <form method="POST"
              action="{{ isset($marchandise) ? route('marchandises.update', $marchandise) : route('marchandises.store') }}"
              novalidate id="marchandiseForm">
            @csrf
            @if(isset($marchandise))
                @method('PUT')
            @endif

            <!-- Client -->
            <div class="mb-6">
                <label for="client_id" class="block text-sm font-medium text-gray-700">
                    Client *
                    <span class="text-gray-500 cursor-help" title="Sélectionnez ou ajoutez un client">ⓘ</span>
                </label>
                <div class="flex gap-2">
                    <select name="client_id" id="client_id" required
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none"
                            aria-label="Sélectionner un client">
                        <option value="">-- Sélectionner un client --</option>
                        @foreach ($clients as $client)
                            <option value="{{ $client->id }}" @selected(old('client_id', $marchandise?->client_id ?? '') == $client->id)>
                                {{ $client->raison_sociale }}
                            </option>
                        @endforeach
                    </select>

                    <button type="button" onclick="openClientModal()"
                            class="mt-1 bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 whitespace-nowrap flex items-center gap-2"
                            aria-label="Ajouter un nouveau client">
                        <x-heroicon-o-plus class="w-5 h-5" aria-hidden="true" /> Ajouter Client
                    </button>
                </div>
                @error('client_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
            </div>
 <!-- Trajet -->
                        <div class="md:col-span-2">
                            <label for="trajet_id" class="block text-sm font-medium text-gray-700">
                                Trajet *
                                <span class="text-gray-500 cursor-help" title="Sélectionnez ou ajoutez un trajet">ⓘ</span>
                            </label>
                            <div class="flex gap-2">
                                <select name="trajet_id" id="marchandises_0_trajet_id" required
                                        class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 focus:ring focus:ring-blue-200 focus:outline-none"
                                        aria-label="Sélectionner un trajet">
                                    <option value="">-- Sélectionner un trajet --</option>
                                    @foreach($trajets as $trajet)
                                        <option value="{{ $trajet->id }}"
                                                @selected(old('marchandises.trajet_id', $marchandise?->trajet_id ?? '') == $trajet->id)>
                                            {{ $trajet->itineraire->lieu_depart }} → {{ $trajet->itineraire->lieu_arrivee }}
                                            ({{ \Carbon\Carbon::parse($trajet->date_depart)->format('d/m/Y') }})
                                        </option>
                                    @endforeach
                                </select>
                                <button type="button" onclick="openTrajetModal()"
                                        class="mt-1 bg-green-600 text-white px-3 py-2 rounded hover:bg-green-700 whitespace-nowrap flex items-center gap-2"
                                        aria-label="Ajouter un nouveau trajet">
                                    <x-heroicon-o-plus class="w-5 h-5" aria-hidden="true" /> Ajouter Trajet
                                </button>
                            </div>
                            @error('trajet_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>




            <!-- Boutons -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('marchandises.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100 flex items-center gap-2"
                   aria-label="Annuler et revenir à la liste">
                    <x-heroicon-o-arrow-left class="w-5 h-5" aria-hidden="true" /> Annuler
                </a>
                <button type="submit" id="submitButton"
                        class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2"
                        aria-label="{{ isset($marchandise) ? 'Mettre à jour la marchandise' : 'Enregistrer les marchandises' }}">
                    <span class="submit-text">
                        @if(isset($marchandise))
                            <x-heroicon-o-pencil class="w-5 h-5" aria-hidden="true" /> Mettre à jour
                        @else
                            <x-heroicon-o-check class="w-5 h-5" aria-hidden="true" /> Enregistrer
                        @endif
                    </span>
                    <span class="loading-text hidden">
                        <i class="fas fa-spinner fa-spin mr-1"></i> Enregistrement...
                    </span>
                </button>
            </div>
        </form>
    </div>
    <!-- Modales -->
    @include('modals.client-create')
    @include('modals.trajet-create')

    <script>
        let marchandiseIndex = {{ isset($marchandise) ? 1 : 1 }};

        // === FONCTIONS POUR MODALE CLIENT ===
        function openClientModal() {
            const modal = document.getElementById('clientModal');
            const modalContent = document.getElementById('clientModalContent');
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            setTimeout(() => {
                modalContent.classList.remove('-translate-y-full', 'scale-95');
                modalContent.classList.add('translate-y-0', 'scale-100');
                document.getElementById('modal_raison_sociale')?.focus();
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeClientModal() {
            const modal = document.getElementById('clientModal');
            const modalContent = document.getElementById('clientModalContent');
            modalContent.classList.remove('translate-y-0', 'scale-100');
            modalContent.classList.add('-translate-y-full', 'scale-95');
            setTimeout(() => {
                modal.classList.remove('opacity-100', 'visible');
                modal.classList.add('opacity-0', 'invisible');
                document.getElementById('clientForm').reset();
                document.body.style.overflow = '';
            }, 300);
        }

        // === FONCTIONS POUR MODALE TRAJET ===
        function openTrajetModal(index) {
            const modal = document.getElementById('trajetModal');
            const modalContent = document.getElementById('trajetModalContent');
            modal.dataset.index = index; // Stocker l'index du bloc de marchandise
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            setTimeout(() => {
                modalContent.classList.remove('-translate-y-full', 'scale-95');
                modalContent.classList.add('translate-y-0', 'scale-100');
                document.getElementById('modal_camion_id')?.focus();
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeTrajetModal() {
            const modal = document.getElementById('trajetModal');
            const modalContent = document.getElementById('trajetModalContent');
            modalContent.classList.remove('translate-y-0', 'scale-100');
            modalContent.classList.add('-translate-y-full', 'scale-95');
            setTimeout(() => {
                modal.classList.remove('opacity-100', 'visible');
                modal.classList.add('opacity-0', 'invisible');
                document.getElementById('trajetForm').reset();
                document.body.style.overflow = '';
            }, 300);
        }

        // === SOUMISSION FORMULAIRE CLIENT ===
        document.getElementById('clientForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Enregistrement...';

            fetch('{{ route('clients.store.ajax') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': formData.get('_token'),
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    const select = document.getElementById('client_id');
                    const option = new Option(data.client.raison_sociale, data.client.id, true, true);
                    select.appendChild(option);
                    closeClientModal();
                    showSuccessMessage('Client ajouté avec succès');
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'ajout du client');
                }
            })
            .catch(error => {
                console.error('Erreur client:', error);
                showErrorMessage('Erreur lors de l\'ajout du client: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

        // === SOUMISSION FORMULAIRE TRAJET ===
        document.getElementById('trajetForm').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Enregistrement...';

        fetch('{{ route('trajets.store.ajax') }}', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': formData.get('_token'),
        'Accept': 'application/json'
    },
    body: formData
})
.then(response => {
                if (!response.ok) {
                    throw new Error('Erreur réseau');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Ajouter le nouveau trajet au select
                    const select = document.getElementById('trajet_id');
                    const optionText = `${data.trajet.lieu_depart} → ${data.trajet.lieu_arrivee} (${data.trajet.date_depart})`;
                    const option = new Option(optionText, data.trajet.id, true, true);
                    select.appendChild(option);

                    closeTrajetModal();
                    showSuccessMessage('Trajet ajouté avec succès');
                } else {
                    throw new Error(data.message || 'Erreur lors de l\'ajout du trajet');
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                showErrorMessage('Erreur lors de l\'ajout du trajet: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });


        // === FONCTIONS UTILITAIRES ===
        function showSuccessMessage(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#10B981',
            }).showToast();
        }

        function showErrorMessage(message) {
            Toastify({
                text: message,
                duration: 5000,
                gravity: 'top',
                position: 'right',
                backgroundColor: '#EF4444',
            }).showToast();
        }

        // === GESTION DES BLOCS DE MARCHANDISE ===
        function addMarchandiseBlock() {
            const container = document.getElementById('marchandisesContainer');
            const template = container.querySelector('.marchandise-block').cloneNode(true);
            template.dataset.index = marchandiseIndex;
            template.querySelector('h3').textContent = `Marchandise ${marchandiseIndex + 1}`;

            // Mettre à jour les noms des champs
            template.querySelectorAll('[name]').forEach(input => {
                const name = input.name.replace(/marchandises\[0\]/, `marchandises[${marchandiseIndex}]`);
                input.name = name;
                input.value = '';
                if (input.tagName === 'SELECT') {
                    input.selectedIndex = 0;
                }
            });

            // Mettre à jour l'ID du select de trajet et le bouton d'ajout de trajet
            const trajetSelect = template.querySelector('select[name$="[trajet_id]"]');
            trajetSelect.id = `marchandises_${marchandiseIndex}_trajet_id`;
            const trajetButton = template.querySelector('button[onclick^="openTrajetModal"]');
            trajetButton.setAttribute('onclick', `openTrajetModal(${marchandiseIndex})`);

            // Afficher le bouton de suppression
            const removeBtn = template.querySelector('button[onclick="removeMarchandiseBlock(this)"]');
            removeBtn.classList.remove('hidden');

            // Réinitialiser les messages d'erreur
            template.querySelectorAll('[data-error]').forEach(error => error.classList.add('hidden'));
            template.querySelectorAll('input, textarea, select').forEach(input => {
                input.classList.remove('border-red-500');
            });

            container.appendChild(template);
            marchandiseIndex++;
        }

        function removeMarchandiseBlock(btn) {
            if (document.querySelectorAll('.marchandise-block').length > 1) {
                btn.closest('.marchandise-block').remove();
            } else {
                showErrorMessage('Vous devez conserver au moins une marchandise.');
            }
        }

        // === VALIDATION DES CHAMPS NUMÉRIQUES ===
        function validateNumber(input) {
            const value = parseFloat(input.value);
            const errorDiv = input.nextElementSibling;
            if (value < 0) {
                errorDiv.classList.remove('hidden');
                input.classList.add('border-red-500');
            } else {
                errorDiv.classList.add('hidden');
                input.classList.remove('border-red-500');
            }
        }

        // === VALIDATION DU FORMULAIRE ===
        function validateForm() {
            let hasErrors = false;
            const clientSelect = document.getElementById('client_id');
            if (!clientSelect.value) {
                clientSelect.classList.add('border-red-500');
                const errorDiv = clientSelect.parentElement.nextElementSibling || document.createElement('p');
                errorDiv.className = 'text-red-600 text-xs mt-1';
                errorDiv.textContent = 'Veuillez sélectionner un client.';
                clientSelect.parentElement.parentElement.appendChild(errorDiv);
                hasErrors = true;
            } else {
                clientSelect.classList.remove('border-red-500');
                const errorDiv = clientSelect.parentElement.nextElementSibling;
                if (errorDiv && errorDiv.tagName === 'P') errorDiv.remove();
            }

            document.querySelectorAll('select[name$="[trajet_id]"]').forEach(select => {
                if (!select.value) {
                    select.classList.add('border-red-500');
                    const errorDiv = select.parentElement.nextElementSibling || document.createElement('p');
                    errorDiv.className = 'text-red-600 text-xs mt-1';
                    errorDiv.textContent = 'Veuillez sélectionner un trajet.';
                    select.parentElement.parentElement.appendChild(errorDiv);
                    hasErrors = true;
                } else {
                    select.classList.remove('border-red-500');
                    const errorDiv = select.parentElement.nextElementSibling;
                    if (errorDiv && errorDiv.tagName === 'P') errorDiv.remove();
                }
            });

            document.querySelectorAll('input[type="number"]').forEach(input => {
                const value = parseFloat(input.value);
                const errorDiv = input.nextElementSibling;
                if (value < 0) {
                    errorDiv.classList.remove('hidden');
                    input.classList.add('border-red-500');
                    hasErrors = true;
                } else {
                    errorDiv.classList.add('hidden');
                    input.classList.remove('border-red-500');
                }
            });

            return !hasErrors;
        }

        // === SOUMISSION FORMULAIRE PRINCIPAL ===
        document.getElementById('marchandiseForm').addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validateForm()) {
                showErrorMessage('Veuillez corriger les erreurs avant de soumettre.');
                return;
            }

            const form = this;
            const submitBtn = document.getElementById('submitButton');
            const originalContent = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.querySelector('.submit-text').classList.add('hidden');
            submitBtn.querySelector('.loading-text').classList.remove('hidden');

            const formData = new FormData(form);
            @if(isset($marchandise))
                formData.delete('_method');
                formData.append('_method', 'PUT');
                form.querySelectorAll('.marchandise-block [name]').forEach(input => {
                    const name = input.name.replace(/marchandises\[0\]\[(\w+)\]/, '$1');
                    formData.set(name, input.value);
                    formData.delete(input.name);
                });
            @endif

            fetch(form.action, {
                method: '{{ isset($marchandise) ? 'PUT' : 'POST' }}',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 422) {
                        return response.json().then(data => {
                            throw new Error(Object.values(data.errors).flat().join(' '));
                        });
                    }
                    throw new Error('Erreur réseau: ' + response.statusText);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    showSuccessMessage(data.message || '{{ isset($marchandise) ? 'Marchandise mise à jour avec succès.' : 'Marchandises ajoutées avec succès.' }}');
                    setTimeout(() => {
                        window.location.href = '{{ route('marchandises.index') }}';
                    }, 1500);
                } else {
                    throw new Error(data.message || 'Erreur lors de la soumission.');
                }
            })
            .catch(error => {
                console.error('Erreur soumission:', error);
                showErrorMessage('Erreur: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.querySelector('.submit-text').classList.remove('hidden');
                submitBtn.querySelector('.loading-text').classList.add('hidden');
                submitBtn.innerHTML = originalContent;
            });
        });

        // === FERMER LES MODALES ===
        document.addEventListener('click', function(e) {
            if (e.target.id === 'clientModal') {
                closeClientModal();
            }
            if (e.target.id === 'trajetModal') {
                closeTrajetModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const clientModal = document.getElementById('clientModal');
                const trajetModal = document.getElementById('trajetModal');
                if (clientModal.classList.contains('opacity-100')) {
                    closeClientModal();
                }
                if (trajetModal.classList.contains('opacity-100')) {
                    closeTrajetModal();
                }
            }
        });

        // === INITIALISATION EN MODE ÉDITION ===
        @if(isset($marchandise))
            document.querySelectorAll('.marchandise-block [name]').forEach(input => {
                input.name = input.name.replace(/marchandises\[0\]\[(\w+)\]/, '$1');
            });
            document.querySelector('button[onclick="addMarchandiseBlock()"]').remove();
        @endif
    </script>
</x-layouts.app>
