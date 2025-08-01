<x-layouts.app>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($transport) ? 'Modifier le transport' : 'Ajouter un nouveau transport' }}
        </h1>

        <form method="POST"
              action="{{ isset($transport) ? route('transports.update', $transport) : route('transports.store') }}"
              novalidate>
            @csrf
            @if(isset($transport))
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
                                    @selected(old('client_id', $transport?->client_id ?? '') == $client->id)>
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
                                        @selected(old('trajet_id', $transport?->trajet_id ?? '') == $trajet->id)>
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

                <!-- Marchandise -->
                <div class="md:col-span-2">
                    <label for="marchandise_id" class="block text-sm font-medium text-gray-700">Marchandise *</label>
                    <select name="marchandise_id" id="marchandise_id" class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" required>
                        <option value="">-- Sélectionner une marchandise --</option>
                        @foreach($marchandises as $marchandise)
                            <option value="{{ $marchandise->id }}"
                                    data-poids-moyen="{{ $marchandise->poids_moyen }}"
                                    data-tarif-par-defaut="{{ $marchandise->tarif_par_defaut }}"
                                    @selected(old('marchandise_id', $transport?->marchandise_id ?? '') == $marchandise->id)>
                                {{ $marchandise->nom }} ({{ $marchandise->unite }})
                            </option>
                        @endforeach
                    </select>
                    @error('marchandise_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Quantité -->
                <div>
                    <label for="quantite" class="block text-sm font-medium text-gray-700">Quantité *</label>
                    <input type="number" step="0.01" name="quantite" id="quantite"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2" required
                           value="{{ old('quantite', $transport?->quantite ?? '') }}">
                    @error('quantite') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Poids (kg) -->
                <div>
                    <label for="poids_kg" class="block text-sm font-medium text-gray-700">Poids total (kg)</label>
                    <input type="number" step="0.01" name="poids_kg" id="poids_kg" readonly
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 bg-gray-100"
                           value="{{ old('poids_kg', $transport?->poids_kg ?? 0) }}">
                    @error('poids_kg') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Volume (m³) -->
                <div>
                    <label for="volume_m3" class="block text-sm font-medium text-gray-700">Volume (m³)</label>
                    <input type="number" step="0.01" name="volume_m3" id="volume_m3"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2"
                           value="{{ old('volume_m3', $transport?->volume_m3 ?? '') }}">
                    @error('volume_m3') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Valeur estimée (Ariary) -->
                <div>
                    <label for="valeur_estimee" class="block text-sm font-medium text-gray-700">Valeur estimée (Ariary)</label>
                    <input type="number" step="0.01" name="valeur_estimee" id="valeur_estimee" readonly
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2 bg-gray-100"
                           value="{{ old('valeur_estimee', $transport?->valeur_estimee ?? 0) }}">
                    @error('valeur_estimee') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Lieu de livraison -->
                <div>
                    <label for="lieu_livraison" class="block text-sm font-medium text-gray-700">Lieu de livraison</label>
                    <input type="text" name="lieu_livraison" id="lieu_livraison"
                           class="mt-1 block w-full rounded border border-gray-300 px-3 py-2"
                           value="{{ old('lieu_livraison', $transport?->lieu_livraison ?? '') }}">
                    @error('lieu_livraison') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="statut" id="statut"
                            class="mt-1 block w-full rounded border border-gray-300 px-3 py-2">
                        <option value="chargee" @selected(old('statut', $transport?->statut ?? '') == 'chargee')>Chargée</option>
                        <option value="en_transit" @selected(old('statut', $transport?->statut ?? '') == 'en_transit')>En transit</option>
                        <option value="livree" @selected(old('statut', $transport?->statut ?? '') == 'livree')>Livrée</option>
                        <option value="retour" @selected(old('statut', $transport?->statut ?? '') == 'retour')>Retour</option>
                    </select>
                    @error('statut') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Boutons -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('transports.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>
                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($transport))
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
        // === Modal Functions (Client and Trajet) ===
        function openClientModal() {
            const modal = document.getElementById('clientModal');
            const modalContent = document.getElementById('clientModalContent');
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            setTimeout(() => {
                modalContent.classList.remove('-translate-y-full', 'scale-95');
                modalContent.classList.add('translate-y-0', 'scale-100');
            }, 10);
            setTimeout(() => document.getElementById('modal_raison_sociale')?.focus(), 350);
            toggleBodyScroll(true);
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
                toggleBodyScroll(false);
            }, 300);
        }

        function openTrajetModal() {
            const modal = document.getElementById('trajetModal');
            const modalContent = document.getElementById('trajetModalContent');
            modal.classList.remove('opacity-0', 'invisible');
            modal.classList.add('opacity-100', 'visible');
            setTimeout(() => {
                modalContent.classList.remove('-translate-y-full', 'scale-95');
                modalContent.classList.add('translate-y-0', 'scale-100');
            }, 10);
            setTimeout(() => document.getElementById('modal_camion_id')?.focus(), 350);
            toggleBodyScroll(true);
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
                toggleBodyScroll(false);
            }, 300);
        }

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
            .then(response => response.ok ? response.json() : Promise.reject('Erreur réseau'))
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
                console.error('Erreur:', error);
                showErrorMessage('Erreur lors de l\'ajout du client: ' + error.message);
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            });
        });

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
            .then(response => response.ok ? response.json() : Promise.reject('Erreur réseau'))
            .then(data => {
                if (data.success) {
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

        // === Calculations ===
        function updateCalculations() {
            const select = document.getElementById('marchandise_id');
            const quantiteInput = document.getElementById('quantite');
            const marchandiseId = select.value;
            const quantite = parseFloat(quantiteInput.value) || 0;

            let totalPoids = 0;
            let totalValeur = 0;

            if (marchandiseId) {
                const poidsMoyen = parseFloat(select.selectedOptions[0].dataset.poidsMoyen) || 0;
                const tarifParDefaut = parseFloat(select.selectedOptions[0].dataset.tarifParDefaut) || 0;
                totalPoids = quantite * poidsMoyen;
                totalValeur = quantite * tarifParDefaut;
            }

            document.getElementById('poids_kg').value = totalPoids.toFixed(2);
            document.getElementById('valeur_estimee').value = totalValeur.toFixed(2);
        }

        document.getElementById('marchandise_id').addEventListener('change', updateCalculations);
        document.getElementById('quantite').addEventListener('input', updateCalculations);

        // Initial calculation
        updateCalculations();

        // === Utility Functions ===
        function showSuccessMessage(message) {
            alert(message); // Replace with your notification system
        }

        function showErrorMessage(message) {
            alert(message); // Replace with your notification system
        }

        document.addEventListener('click', function (e) {
            if (e.target.id === 'clientModal') closeClientModal();
            if (e.target.id === 'trajetModal') closeTrajetModal();
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                const clientModal = document.getElementById('clientModal');
                const trajetModal = document.getElementById('trajetModal');
                if (clientModal.classList.contains('opacity-100')) closeClientModal();
                if (trajetModal.classList.contains('opacity-100')) closeTrajetModal();
            }
        });

        function toggleBodyScroll(disable) {
            document.body.style.overflow = disable ? 'hidden' : '';
        }
    </script>
</x-layouts.app>
