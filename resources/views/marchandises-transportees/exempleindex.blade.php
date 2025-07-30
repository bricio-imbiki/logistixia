<x-layouts.app>
    <!-- Toastify pour notifications -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <!-- Flash message -->
    @if (session('success'))
        <div class="mt-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="p-6 bg-white rounded-xl shadow-md">
        <!-- Titre + Bouton Ajouter -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <x-heroicon-o-archive-box class="w-6 h-6 text-blue-600" aria-hidden="true" />
                <span>Gestion des marchandises</span>
            </h2>
            <a href="{{ route('marchandises.create') }}"
               class="no-underline inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded-lg shadow transition"
               aria-label="Ajouter une nouvelle marchandise">
                <x-heroicon-o-plus class="w-5 h-5" aria-hidden="true" />
                Ajouter
            </a>
        </div>

        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('marchandises.index') }}" class="mb-4 relative" id="searchForm">
            <div class="relative">
                <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" aria-hidden="true" />
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                       placeholder="Rechercher par description, lieu, etc."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
                       aria-label="Rechercher des marchandises">
                <div id="loadingSpinner" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                    <svg class="w-5 h-5 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
        </form>

        <!-- Tableau -->
        <div class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700" id="marchandisesTable">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable(0)" data-sort="description" data-order="asc">
                            Description
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable(1)" data-sort="client" data-order="asc">
                            Client
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable(2)" data-sort="trajet" data-order="asc">
                            Trajet
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable(3)" data-sort="poids_kg" data-order="asc">
                            Poids (kg)
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable(4)" data-sort="volume_m3" data-order="asc">
                            Volume (m³)
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable(5)" data-sort="valeur_estimee" data-order="asc">
                            Valeur estimée
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable(6)" data-sort="lieu_livraison" data-order="asc">
                            Lieu livraison
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable(7)" data-sort="statut" data-order="asc">
                            Statut
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="marchandisesBody">
                    @forelse ($marchandises as $marchandise)
                        <tr class="border-b hover:bg-gray-50 transition cursor-pointer"
                            onclick="highlightRow(this)"
                            data-id="{{ $marchandise->id }}"
                            data-description="{{ Str::limit($marchandise->description ?? '-', 40) }}"
                            data-client="{{ $marchandise->client->raison_sociale ?? '-' }}"
                            data-trajet="{{ $marchandise->trajet && $marchandise->trajet->itineraire ? $marchandise->trajet->itineraire->lieu_depart . ' → ' . $marchandise->trajet->itineraire->lieu_arrivee . ' (' . \Carbon\Carbon::parse($marchandise->trajet->date_depart)->format('d/m/Y') . ')' : '-' }}"
                            data-poids_kg="{{ $marchandise->poids_kg ?? '-' }}"
                            data-volume_m3="{{ $marchandise->volume_m3 ?? '-' }}"
                            data-valeur_estimee="{{ $marchandise->valeur_estimee ?? '-' }}"
                            data-lieu_livraison="{{ $marchandise->lieu_livraison ?? '-' }}"
                            data-statut="{{ $marchandise->statut ?? '-' }}"
                            role="row">
                            <td class="px-4 py-2 text-left">{{ Str::limit($marchandise->description ?? '-', 40) }}</td>
                            <td class="px-4 py-2 text-left">{{ $marchandise->client->raison_sociale ?? '-' }}</td>
                            <td class="px-4 py-2 text-left">
                                @if ($marchandise->trajet && $marchandise->trajet->itineraire)
                                    {{ $marchandise->trajet->itineraire->lieu_depart }} →
                                    {{ $marchandise->trajet->itineraire->lieu_arrivee }}
                                    ({{ \Carbon\Carbon::parse($marchandise->trajet->date_depart)->format('d/m/Y') }})
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2">{{ $marchandise->poids_kg ?? '-' }}</td>
                            <td class="px-4 py-2">{{ $marchandise->volume_m3 ?? '-' }}</td>
                            <td class="px-4 py-2">{{ number_format($marchandise->valeur_estimee, 0, ',', ' ') ?? '-' }} Ar</td>
                            <td class="px-4 py-2 text-left">{{ $marchandise->lieu_livraison ?? '-' }}</td>
                            <td class="px-4 py-2">
                                <span @class([
                                    'bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold' => $marchandise->statut === 'chargee',
                                    'bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-semibold' => $marchandise->statut === 'en_transit',
                                    'bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold' => $marchandise->statut === 'livree',
                                    'bg-red-100 text-red-800 px-2 py-1 rounded text-xs font-semibold' => $marchandise->statut === 'retour',
                                ])>
                                    {{ ucfirst(str_replace('_', ' ', $marchandise->statut)) }}
                                </span>
                            </td>
                            <td class="px-4 py-2">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('marchandises.edit', $marchandise->id) }}"
                                       class="text-blue-600 hover:text-blue-800 transition"
                                       title="Modifier"
                                       aria-label="Modifier la marchandise">
                                        <x-heroicon-o-pencil class="w-5 h-5" aria-hidden="true" />
                                    </a>
                                    <form action="{{ route('marchandises.destroy', $marchandise->id) }}"
                                          method="POST"
                                          class="delete-form"
                                          data-id="{{ $marchandise->id }}">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                class="text-red-600 hover:text-red-800 transition"
                                                title="Supprimer"
                                                aria-label="Supprimer la marchandise">
                                            <x-heroicon-o-trash class="w-5 h-5" aria-hidden="true" />
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-6 text-gray-500">Aucune marchandise trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $marchandises->links() }}
        </div>
    </div>

    <script>
        // Debounce function to limit search frequency
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Client-side search
        const searchInput = document.getElementById('searchInput');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const marchandisesBody = document.getElementById('marchandisesBody');
        const rows = Array.from(marchandisesBody.querySelectorAll('tr[data-id]'));

        searchInput.addEventListener('input', debounce(function () {
            const query = searchInput.value.toLowerCase().trim();
            loadingSpinner.classList.remove('hidden');

            setTimeout(() => {
                rows.forEach(row => {
                    const description = row.dataset.description.toLowerCase();
                    const client = row.dataset.client.toLowerCase();
                    const trajet = row.dataset.trajet.toLowerCase();
                    const lieu_livraison = row.dataset.lieu_livraison.toLowerCase();
                    const statut = row.dataset.statut.toLowerCase();

                    const matches = description.includes(query) ||
                                   client.includes(query) ||
                                   trajet.includes(query) ||
                                   lieu_livraison.includes(query) ||
                                   statut.includes(query);

                    row.style.display = matches ? '' : 'none';
                });

                // Show "Aucune marchandise" if no rows are visible
                const visibleRows = rows.filter(row => row.style.display !== 'none');
                if (visibleRows.length === 0) {
                    if (!marchandisesBody.querySelector('.no-results')) {
                        const noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'no-results';
                        noResultsRow.innerHTML = '<td colspan="9" class="text-center py-6 text-gray-500">Aucune marchandise trouvée.</td>';
                        marchandisesBody.appendChild(noResultsRow);
                    }
                } else {
                    const noResultsRow = marchandisesBody.querySelector('.no-results');
                    if (noResultsRow) noResultsRow.remove();
                }

                loadingSpinner.classList.add('hidden');
            }, 300);
        }, 500));

        // Sorting function
        function sortTable(columnIndex) {
            const th = document.querySelectorAll('th')[columnIndex];
            const sortKey = th.dataset.sort;
            const order = th.dataset.order === 'asc' ? 'desc' : 'asc';
            th.dataset.order = order;

            // Update sort indicators
            document.querySelectorAll('th').forEach(header => {
                const icon = header.querySelector('svg');
                if (header === th) {
                    icon.classList.toggle('rotate-180', order === 'desc');
                } else {
                    header.dataset.order = 'asc';
                    if (header.querySelector('svg')) {
                        header.querySelector('svg').classList.remove('rotate-180');
                    }
                }
            });

            rows.sort((a, b) => {
                let aValue = a.dataset[sortKey] || '';
                let bValue = b.dataset[sortKey] || '';

                // Handle numeric columns
                if (['poids_kg', 'volume_m3', 'valeur_estimee'].includes(sortKey)) {
                    aValue = aValue === '-' ? -Infinity : parseFloat(aValue);
                    bValue = bValue === '-' ? -Infinity : parseFloat(bValue);
                } else {
                    aValue = aValue.toLowerCase();
                    bValue = bValue.toLowerCase();
                }

                if (aValue < bValue) return order === 'asc' ? -1 : 1;
                if (aValue > bValue) return order === 'asc' ? 1 : -1;
                return 0;
            });

            // Re-append sorted rows
            marchandisesBody.innerHTML = '';
            rows.forEach(row => marchandisesBody.appendChild(row));
            if (rows.length === 0 || rows.every(row => row.style.display === 'none')) {
                marchandisesBody.innerHTML = '<tr><td colspan="9" class="text-center py-6 text-gray-500">Aucune marchandise trouvée.</td></tr>';
            }
        }

        // Highlight selected row
        let selectedRow = null;
        function highlightRow(row) {
            if (selectedRow) {
                selectedRow.classList.remove('bg-blue-100');
            }
            row.classList.add('bg-blue-100');
            selectedRow = row;

            // Allow keyboard navigation
            row.focus();
        }

        // Toastify delete confirmation
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();
                const form = this;
                Toastify({
                    text: 'Supprimer cette marchandise ?',
                    duration: -1,
                    close: true,
                    gravity: 'top',
                    position: 'center',
                    backgroundColor: '#EF4444',
                    buttons: [
                        {
                            text: 'Annuler',
                            callback: (toast) => toast.remove(),
                            className: 'bg-gray-300 text-gray-800 px-4 py-1 rounded mr-2',
                        },
                        {
                            text: 'Confirmer',
                            callback: (toast) => {
                                form.submit();
                                toast.remove();
                            },
                            className: 'bg-red-600 text-white px-4 py-1 rounded',
                        }
                    ]
                }).showToast();
            });
        });

        // Keyboard navigation for rows
        document.addEventListener('keydown', function (e) {
            const visibleRows = rows.filter(row => row.style.display !== 'none');
            if (visibleRows.length === 0) return;

            let currentIndex = selectedRow ? visibleRows.indexOf(selectedRow) : -1;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentIndex = Math.min(currentIndex + 1, visibleRows.length - 1);
                highlightRow(visibleRows[currentIndex]);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentIndex = Math.max(currentIndex - 1, 0);
                highlightRow(visibleRows[currentIndex]);
            } else if (e.key === 'Enter' && selectedRow) {
                e.preventDefault();
                const editLink = selectedRow.querySelector('a[href*="edit"]');
                if (editLink) window.location.href = editLink.href;
            }
        });

        // Initialize search if query exists
        if (searchInput.value) {
            searchInput.dispatchEvent(new Event('input'));
        }
    </script>
</x-layouts.app>
