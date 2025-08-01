<x-layouts.app>
    <style>
        /* Custom scrollbar for better visibility */
        .custom-scroll::-webkit-scrollbar {
            width: 8px;
        }

        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .custom-scroll::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }

        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
    </style>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Marchandises</h1>
            <button id="open-create-modal"
                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2.5 rounded-lg flex items-center gap-2 transition duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouvelle Marchandise
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="relative flex-1">
                <input type="text" id="search-input" placeholder="Rechercher par nom, référence ou catégorie..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <svg class="absolute left-3 top-3 w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>
            <select id="sort-select"
                class="border border-gray-300 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-blue-500">
                <option value="">Trier par...</option>
                <option value="nom">Nom</option>
                <option value="tarif">Tarif</option>
                <option value="poids">Poids</option>
            </select>
        </div>

        <!-- Notification Area -->
        <div id="global-notification" class="hidden p-4 mb-4 rounded-lg text-sm"></div>

        <!-- Table Section -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            @if ($marchandises->isEmpty())
                <div class="p-6 text-center text-gray-500">
                    Aucune marchandise trouvée. <button id="open-create-modal-empty"
                        class="text-blue-600 hover:underline">Ajoutez-en une</button>.
                </div>
            @else
                <div class="overflow-x-auto">
                    <table id="marchandises-table" class="w-full text-sm text-left text-gray-700">
                        <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-semibold">
                            <tr>
                                <th scope="col" class="px-6 py-3">Nom</th>
                                <th scope="col" class="px-6 py-3">Réf.</th>
                                <th scope="col" class="px-6 py-3">Catégorie</th>
                                <th scope="col" class="px-6 py-3">Poids</th>
                                <th scope="col" class="px-6 py-3">Unité</th>
                                <th scope="col" class="px-6 py-3">Tarif</th>
                                <th scope="col" class="px-6 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            @foreach ($marchandises as $m)
                                <tr class="border-t hover:bg-gray-50 transition duration-100"
                                    data-id="{{ $m->id }}">
                                    <td class="px-6 py-4 font-medium text-gray-900">{{ $m->nom }}</td>
                                    <td class="px-6 py-4">{{ $m->reference }}</td>
                                    <td class="px-6 py-4">{{ $m->categorie }}</td>
                                    <td class="px-6 py-4">{{ $m->poids_moyen }}</td>
                                    <td class="px-6 py-4">{{ $m->unite }}</td>
                                    <td class="px-6 py-4">{{ number_format($m->tarif_par_defaut, 0, ',', ' ') }} Ar</td>
                                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                                        <button class="open-edit-modal text-blue-600 hover:text-blue-800"
                                            data-id="{{ $m->id }}" data-nom="{{ $m->nom }}"
                                            data-reference="{{ $m->reference }}" data-categorie="{{ $m->categorie }}"
                                            data-poids="{{ $m->poids_moyen }}" data-unite="{{ $m->unite }}"
                                            data-tarif="{{ $m->tarif_par_defaut }}" title="Modifier">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z">
                                                </path>
                                            </svg>
                                        </button>
                                        <form action="{{ route('marchandises.destroy', $m) }}" method="POST"
                                            onsubmit="return confirm('Supprimer cette marchandise ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800"
                                                title="Supprimer">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                    </path>
                                                </svg>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                <div class="p-4 border-t">
                    {{ $marchandises->links() }}
                </div>
            @endif
        </div>

        <!-- Modal -->
        <div id="form-modal"
            class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center py-6 sm:py-8 px-4 z-50"
            role="dialog" aria-labelledby="modal-title" aria-modal="true">
            <div id="modal-content" class="bg-white rounded-xl shadow-lg max-w-md w-full min-h-[200px] flex flex-col">
                <div class="flex justify-between items-center p-4 border-b">
                    <h2 id="modal-title" class="text-lg font-semibold text-gray-900">Nouvelle Marchandise</h2>
                    <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto custom-scroll p-4">
                    <form id="marchandise-form">
                        @csrf
                        <input type="hidden" id="form-id" name="id">
                        <!-- Notification Area -->
                        <div id="form-notification" class="hidden p-3 mb-3 rounded-lg text-xs"></div>
                        <!-- Nom -->
                        <div class="mb-3">
                            <label for="nom" class="block text-xs font-medium text-gray-700 mb-1">Nom</label>
                            <input type="text" id="nom" name="nom"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                placeholder="Ex. Riz blanc" required autofocus>
                            <p id="nom-error" class="mt-1 text-xs text-red-600 hidden"></p>
                        </div>
                        <!-- Référence -->
                        <div class="mb-3">
                            <label for="reference"
                                class="block text-xs font-medium text-gray-700 mb-1">Référence</label>
                            <input type="text" id="reference" name="reference"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                placeholder="Ex. RIZ-001">
                        </div>
                        <!-- Catégorie -->
                        <div class="mb-3">
                            <label for="categorie"
                                class="block text-xs font-medium text-gray-700 mb-1">Catégorie</label>
                            <input type="text" id="categorie" name="categorie"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                placeholder="Ex. Alimentaire">
                        </div>
                        <!-- Poids -->
                        <div class="mb-3">
                            <label for="poids_moyen" class="block text-xs font-medium text-gray-700 mb-1">Poids
                                moyen</label>
                            <input type="number" id="poids_moyen" name="poids_moyen" step="0.01"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                placeholder="Ex. 1.5" min="0">
                            <p id="poids_moyen-error" class="mt-1 text-xs text-red-600 hidden"></p>
                        </div>
                        <!-- Unité -->
                        <div class="mb-3">
                            <label for="unite" class="block text-xs font-medium text-gray-700 mb-1">Unité</label>
                            <select id="unite" name="unite"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs">
                                <option value="" disabled selected>Choisir une unité</option>
                                <option value="kg">Kilogramme (kg)</option>
                                <option value="g">Gramme (g)</option>
                                <option value="L">Litre (L)</option>
                                <option value="unité">Unité</option>
                            </select>
                            <p id="unite-error" class="mt-1 text-xs text-red-600 hidden"></p>
                        </div>
                        <!-- Tarif -->
                        <div class="mb-3">
                            <label for="tarif_par_defaut" class="block text-xs font-medium text-gray-700 mb-1">Tarif
                                par défaut (Ar)</label>
                            <input type="number" id="tarif_par_defaut" name="tarif_par_defaut"
                                class="w-full px-2.5 py-1.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-xs"
                                placeholder="Ex. 5000" min="0">
                            <p id="tarif_par_defaut-error" class="mt-1 text-xs text-red-600 hidden"></p>
                        </div>
                        <!-- Buttons -->
                        <div class="flex justify-end gap-2">
                            <button type="button" id="cancel-btn"
                                class="px-3 py-1.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium text-xs">
                                Annuler
                            </button>
                            <button type="submit" id="submit-btn"
                                class="px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-1.5 text-xs">
                                <span id="submit-text">Créer</span>
                                <svg id="submit-spinner" class="hidden w-3.5 h-3.5 animate-spin" fill="none"
                                    viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                </svg>
                            </button>
                            <button type="button" id="create-another-btn"
                                class="hidden px-3 py-1.5 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium text-xs">
                                Créer et ajouter un autre
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- JavaScript for Modal and AJAX -->
    <script>
        const modal = document.getElementById('form-modal');
        const modalContent = document.getElementById('modal-content');
        const form = document.getElementById('marchandise-form');
        const notification = document.getElementById('form-notification');
        const globalNotification = document.getElementById('global-notification');
        const submitBtn = document.getElementById('submit-btn');
        const submitText = document.getElementById('submit-text');
        const submitSpinner = document.getElementById('submit-spinner');
        const cancelBtn = document.getElementById('cancel-btn');
        const createAnotherBtn = document.getElementById('create-another-btn');
        const tableBody = document.getElementById('table-body');

        // Dynamically adjust modal height
        function adjustModalHeight() {
            const viewportHeight = window.innerHeight;
            const taskbarHeight = 48; // Approximate Windows taskbar height
            const padding = window.innerWidth < 640 ? 48 : 64; // 6rem (48px) mobile, 8rem (64px) desktop
            const maxHeight = viewportHeight - padding - taskbarHeight;
            modalContent.style.maxHeight = `${maxHeight}px`;
        }

        window.addEventListener('resize', adjustModalHeight);
        document.addEventListener('DOMContentLoaded', adjustModalHeight);

        // Open modal for create
        document.getElementById('open-create-modal').addEventListener('click', openCreateModal);
        document.getElementById('open-create-modal-empty')?.addEventListener('click', openCreateModal);

        // Open modal for edit
        document.querySelectorAll('.open-edit-modal').forEach(btn => {
            btn.addEventListener('click', () => openEditModal(btn));
        });

        // Close modal
        document.getElementById('close-modal').addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', e => e.target === modal && closeModal());
        document.addEventListener('keydown', e => e.key === 'Escape' && closeModal());

        // Form submission
        form.addEventListener('submit', async e => {
            e.preventDefault();
            const formData = new FormData(form);
            const isEdit = !!formData.get('id');
            const url = isEdit ? `/marchandises/${formData.get('id')}` : '/marchandises';
            const method = isEdit ? 'PUT' : 'POST';

            // Validate
            let errors = [];
            if (!formData.get('nom').trim()) errors.push('Le nom est requis.');
            if (!formData.get('unite')) errors.push('Veuillez sélectionner une unité.');
            if (formData.get('poids_moyen') && formData.get('poids_moyen') < 0) errors.push(
                'Le poids ne peut pas être négatif.');
            if (formData.get('tarif_par_defaut') && formData.get('tarif_par_defaut') < 0) errors.push(
                'Le tarif ne peut pas être négatif.');

            if (errors.length) {
                showNotification(notification, errors.join('<br>'), 'error');
                scrollToTopOfForm();
                return;
            }

            // Show loading
            submitBtn.disabled = true;
            submitText.classList.add('hidden');
            submitSpinner.classList.remove('hidden');

            try {
                const response = await fetch(url, {
                    method,
                    headers: {
                        'X-CSRF-TOKEN': formData.get('_token'),
                        'Accept': 'application/json'
                    },
                    body: formData
                });

                const data = await response.json();
                if (!response.ok) throw new Error(data.message || 'Erreur serveur');

                // Update table
                if (isEdit) {
                    updateTableRow(data);
                } else {
                    addTableRow(data);
                    createAnotherBtn.classList.remove('hidden');
                }

                showNotification(globalNotification,
                    `Marchandise ${isEdit ? 'modifiée' : 'créée'} avec succès.`, 'success');
                if (!formData.get('create_another')) closeModal();
                else resetForm();
            } catch (error) {
                showNotification(notification, error.message, 'error');
                scrollToTopOfForm();
            } finally {
                submitBtn.disabled = false;
                submitText.classList.remove('hidden');
                submitSpinner.classList.add('hidden');
            }
        });

        // Create another
        createAnotherBtn.addEventListener('click', () => {
            form.querySelector('input[name="create_another"]').value = '1';
            form.dispatchEvent(new Event('submit'));
        });

        function openCreateModal() {
            resetForm();
            submitText.textContent = 'Créer';
            document.getElementById('modal-title').textContent = 'Nouvelle Marchandise';
            modal.classList.remove('hidden');
            adjustModalHeight();
            document.getElementById('nom').focus();
            scrollToTopOfForm();
        }

        function openEditModal(btn) {
            resetForm();
            const data = btn.dataset;
            document.getElementById('form-id').value = data.id;
            document.getElementById('nom').value = data.nom;
            document.getElementById('reference').value = data.reference;
            document.getElementById('categorie').value = data.categorie;
            document.getElementById('poids_moyen').value = data.poids;
            document.getElementById('unite').value = data.unite;
            document.getElementById('tarif_par_defaut').value = data.tarif;
            submitText.textContent = 'Modifier';
            document.getElementById('modal-title').textContent = 'Modifier Marchandise';
            modal.classList.remove('hidden');
            adjustModalHeight();
            document.getElementById('nom').focus();
            scrollToTopOfForm();
        }

        function closeModal() {
            modal.classList.add('hidden');
            resetForm();
        }

        function resetForm() {
            form.reset();
            document.getElementById('form-id').value = '';
            createAnotherBtn.classList.add('hidden');
            notification.classList.add('hidden');
            form.querySelectorAll('.text-red-600').forEach(el => el.classList.add('hidden'));
            form.querySelector('input[name="create_another"]')?.remove();
        }

        function showNotification(element, message, type) {
            element.classList.remove('hidden', 'bg-green-50', 'text-green-700', 'bg-red-50', 'text-red-700');
            element.classList.add(type === 'success' ? 'bg-green-50' : 'bg-red-50', type === 'success' ? 'text-green-700' :
                'text-red-700');
            element.innerHTML = message;
        }

        function scrollToTopOfForm() {
            const formContainer = form.parentElement;
            formContainer.scrollTop = 0;
        }

        function addTableRow(data) {
            const row = document.createElement('tr');
            row.className = 'border-t hover:bg-gray-50 transition duration-100';
            row.dataset.id = data.id;
            row.innerHTML = `
                    <td class="px-6 py-4 font-medium text-gray-900">${data.nom}</td>
                    <td class="px-6 py-4">${data.reference || ''}</td>
                    <td class="px-6 py-4">${data.categorie || ''}</td>
                    <td class="px-6 py-4">${data.poids_moyen || ''}</td>
                    <td class="px-6 py-4">${data.unite || ''}</td>
                    <td class="px-6 py-4">${data.tarif_par_defaut ? data.tarif_par_defaut.toLocaleString('fr-FR') + ' Ar' : ''}</td>
                    <td class="px-6 py-4 text-right flex justify-end gap-2">
                        <button class="open-edit-modal text-blue-600 hover:text-blue-800" data-id="${data.id}"
                                data-nom="${data.nom}" data-reference="${data.reference || ''}" data-categorie="${data.categorie || ''}"
                                data-poids="${data.poids_moyen || ''}" data-unite="${data.unite || ''}" data-tarif="${data.tarif_par_defaut || ''}"
                                title="Modifier">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                        </button>
                        <form action="/marchandises/${data.id}" method="POST" onsubmit="return confirm('Supprimer cette marchandise ?')">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                `;
            tableBody.prepend(row);
            row.querySelector('.open-edit-modal').addEventListener('click', () => openEditModal(row.querySelector(
                '.open-edit-modal')));
        }

        function updateTableRow(data) {
            const row = tableBody.querySelector(`tr[data-id="${data.id}"]`);
            if (row) {
                row.innerHTML = `
                        <td class="px-6 py-4 font-medium text-gray-900">${data.nom}</td>
                        <td class="px-6 py-4">${data.reference || ''}</td>
                        <td class="px-6 py-4">${data.categorie || ''}</td>
                        <td class="px-6 py-4">${data.poids_moyen || ''}</td>
                        <td class="px-6 py-4">${data.unite || ''}</td>
                        <td class="px-6 py-4">${data.tarif_par_defaut ? data.tarif_par_defaut.toLocaleString('fr-FR') + ' Ar' : ''}</td>
                        <td class="px-6 py-4 text-right flex justify-end gap-2">
                            <button class="open-edit-modal text-blue-600 hover:text-blue-800" data-id="${data.id}"
                                    data-nom="${data.nom}" data-reference="${data.reference || ''}" data-categorie="${data.categorie || ''}"
                                    data-poids="${data.poids_moyen || ''}" data-unite="${data.unite || ''}" data-tarif="${data.tarif_par_defaut || ''}"
                                    title="Modifier">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                </svg>
                            </button>
                            <form action="/marchandises/${data.id}" method="POST" onsubmit="return confirm('Supprimer cette marchandise ?')">
                                <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="text-red-600 hover:text-red-800" title="Supprimer">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </td>
                    `;
                row.querySelector('.open-edit-modal').addEventListener('click', () => openEditModal(row.querySelector(
                    '.open-edit-modal')));
            }
        }
    </script>

</x-layouts.app>
