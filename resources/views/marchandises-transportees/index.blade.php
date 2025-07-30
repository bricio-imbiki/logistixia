{{-- <!-- resources/views/marchandises/index.blade.php -->
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

        <!-- Panneau de statistiques -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-100 p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-blue-800">Total Marchandises</h3>
                <p class="text-2xl font-bold text-blue-900">{{ $marchandises->total() }}</p>
            </div>
            <div class="bg-green-100 p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-green-800">Chargées</h3>
                <p class="text-2xl font-bold text-green-900">{{ $stats['chargee'] ?? 0 }}</p>
            </div>
            <div class="bg-yellow-100 p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-yellow-800">En Transit</h3>
                <p class="text-2xl font-bold text-yellow-900">{{ $stats['en_transit'] ?? 0 }}</p>
            </div>
            <div class="bg-red-100 p-4 rounded-lg shadow">
                <h3 class="text-sm font-medium text-red-800">Retours</h3>
                <p class="text-2xl font-bold text-red-900">{{ $stats['retour'] ?? 0 }}</p>
            </div>
        </div>

        <!-- Bascule entre vues -->
        <div class="mb-4 flex gap-2" id="viewToggle">
            <button onclick="toggleView('table')"
                    class="px-4 py-2 rounded bg-blue-600 text-white"
                    id="tableViewBtn"
                    aria-label="Afficher la vue tableau">
                Vue Tableau
            </button>
            <button onclick="toggleView('grouped')"
                    class="px-4 py-2 rounded bg-gray-200"
                    id="groupedViewBtn"
                    aria-label="Afficher la vue groupée par client">
                Vue par Client
            </button>
        </div>

        <!-- Barre de recherche et filtres -->
        <form method="GET" action="{{ route('marchandises.index') }}" class="mb-6 flex flex-col md:flex-row gap-4" id="filterForm">
            <div class="relative w-full md:w-1/3">
                <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" aria-hidden="true" />
                <input type="text" name="search" id="searchInput" value="{{ request('search') }}"
                       placeholder="Rechercher par description, lieu..."
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
                       aria-label="Rechercher des marchandises">
                <div id="loadingSpinner" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
                    <svg class="w-5 h-5 animate-spin text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            <select name="client_id" id="clientFilter" class="w-full md:w-1/4 rounded border border-gray-300 px-3 py-2"
                    aria-label="Filtrer par client">
                <option value="">Tous les clients</option>
                @foreach ($clients as $client)
                    <option value="{{ $client->id }}" @selected(request('client_id') == $client->id)>
                        {{ $client->raison_sociale }}
                    </option>
                @endforeach
            </select>
            <select name="statut" id="statutFilter" class="w-full md:w-1/4 rounded border border-gray-300 px-3 py-2"
                    aria-label="Filtrer par statut">
                <option value="">Tous les statuts</option>
                <option value="chargee" @selected(request('statut') == 'chargee')>Chargée</option>
                <option value="en_transit" @selected(request('statut') == 'en_transit')>En transit</option>
                <option value="livree" @selected(request('statut') == 'livree')>Livrée</option>
                <option value="retour" @selected(request('statut') == 'retour')>Retour</option>
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
                    aria-label="Appliquer les filtres">
                Filtrer
            </button>
        </form>

        <!-- Vue Tableau -->
        <div id="tableView" class="overflow-x-auto">
            <table class="w-full table-auto border-collapse text-sm text-gray-700" id="marchandisesTable">
                <thead>
                    <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable('description', this)" data-sort="description" data-order="asc">
                            Description
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable('client', this)" data-sort="client" data-order="asc">
                            Client
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable('trajet', this)" data-sort="trajet" data-order="asc">
                            Trajet
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable('poids_kg', this)" data-sort="poids_kg" data-order="asc">
                            Poids (kg)
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable('volume_m3', this)" data-sort="volume_m3" data-order="asc">
                            Volume (m³)
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable('valeur_estimee', this)" data-sort="valeur_estimee" data-order="asc">
                            Valeur estimée
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable('lieu_livraison', this)" data-sort="lieu_livraison" data-order="asc">
                            Lieu livraison
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 cursor-pointer" onclick="sortTable('statut', this)" data-sort="statut" data-order="asc">
                            Statut
                            <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                        </th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    @forelse ($marchandises as $marchandise)
                        <tr class="border-b hover:bg-gray-50 transition duration-200 cursor-pointer"
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
                            data-client_id="{{ $marchandise->client_id ?? '' }}"
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
                                       title="Modifier la marchandise" aria-label="Modifier la marchandise">
                                        <x-heroicon-o-pencil class="w-5 h-5" aria-hidden="true" />
                                    </a>
                                    <button onclick="openDeleteModal({{ $marchandise->id }})"
                                            class="text-red-600 hover:text-red-800 transition"
                                            title="Supprimer la marchandise" aria-label="Supprimer la marchandise">
                                        <x-heroicon-o-trash class="w-5 h-5" aria-hidden="true" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr class="no-results">
                            <td colspan="9" class="text-center py-6 text-gray-500">Aucune marchandise trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Vue groupée par client -->
        <div id="groupedView" class="space-y-4 hidden">
            @foreach ($clients as $client)
                <div class="bg-gray-50 p-4 rounded-lg shadow">
                    <h3 class="text-lg font-semibold">{{ $client->raison_sociale }}</h3>
                    <table class="w-full table-auto border-collapse text-sm text-gray-700 mt-2" data-client_id="{{ $client->id }}">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-left">
                                <th class="px-4 py-2 cursor-pointer" onclick="sortGroupedTable('description', this, {{ $client->id }})" data-sort="description" data-order="asc">
                                    Description
                                    <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                                </th>
                                <th class="px-4 py-2 cursor-pointer" onclick="sortGroupedTable('trajet', this, {{ $client->id }})" data-sort="trajet" data-order="asc">
                                    Trajet
                                    <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                                </th>
                                <th class="px-4 py-2 cursor-pointer" onclick="sortGroupedTable('statut', this, {{ $client->id }})" data-sort="statut" data-order="asc">
                                    Statut
                                    <x-heroicon-o-arrows-up-down class="w-4 h-4 inline ml-1" aria-hidden="true" />
                                </th>
                                <th class="px-4 py-2 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="client-body" data-client_id="{{ $client->id }}">
                            @foreach ($client->marchandises as $marchandise)
                                <tr class="border-b hover:bg-gray-50 transition duration-200 cursor-pointer"
                                    onclick="highlightRow(this)"
                                    data-id="{{ $marchandise->id }}"
                                    data-description="{{ Str::limit($marchandise->description ?? '-', 40) }}"
                                    data-trajet="{{ $marchandise->trajet && $marchandise->trajet->itineraire ? $marchandise->trajet->itineraire->lieu_depart . ' → ' . $marchandise->trajet->itineraire->lieu_arrivee : '-' }}"
                                    data-statut="{{ $marchandise->statut ?? '-' }}"
                                    data-client_id="{{ $marchandise->client_id ?? '' }}"
                                    role="row">
                                    <td class="px-4 py-2">{{ Str::limit($marchandise->description ?? '-', 40) }}</td>
                                    <td class="px-4 py-2">
                                        {{ $marchandise->trajet?->itineraire->lieu_depart ?? '-' }} →
                                        {{ $marchandise->trajet?->itineraire->lieu_arrivee ?? '-' }}
                                    </td>
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
                                               title="Modifier la marchandise" aria-label="Modifier la marchandise">
                                                <x-heroicon-o-pencil class="w-5 h-5" aria-hidden="true" />
                                            </a>
                                            <button onclick="openDeleteModal({{ $marchandise->id }})"
                                                    class="text-red-600 hover:text-red-800 transition"
                                                    title="Supprimer la marchandise" aria-label="Supprimer la marchandise">
                                                <x-heroicon-o-trash class="w-5 h-5" aria-hidden="true" />
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6" id="pagination">
            {{ $marchandises->appends(request()->query())->links() }}
        </div>

        <!-- Modale de suppression -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 transition-opacity duration-300" role="dialog" aria-labelledby="deleteModalTitle">
            <div class="bg-white rounded-lg p-6 max-w-md w-full transform transition-transform duration-300 scale-95">
                <h3 id="deleteModalTitle" class="text-lg font-semibold mb-4">Confirmer la suppression</h3>
                <p class="text-gray-600 mb-6">Voulez-vous vraiment supprimer cette marchandise ?</p>
                <div class="flex justify-end gap-3">
                    <button onclick="closeDeleteModal()"
                            class="px-4 py-2 border rounded text-gray-600 hover:bg-gray-100"
                            aria-label="Annuler la suppression">
                        Annuler
                    </button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700"
                                aria-label="Confirmer la suppression">
                            Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Debounce function to limit filtering frequency
        function debounce(func, wait) {
            let timeout;
            return function (...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Toggle between table and grouped views
        function toggleView(view) {
            const tableView = document.getElementById('tableView');
            const groupedView = document.getElementById('groupedView');
            const tableBtn = document.getElementById('tableViewBtn');
            const groupedBtn = document.getElementById('groupedViewBtn');

            if (view === 'table') {
                tableView.classList.remove('hidden');
                groupedView.classList.add('hidden');
                tableBtn.classList.add('bg-blue-600', 'text-white');
                tableBtn.classList.remove('bg-gray-200');
                groupedBtn.classList.add('bg-gray-200');
                groupedBtn.classList.remove('bg-blue-600', 'text-white');
            } else {
                tableView.classList.add('hidden');
                groupedView.classList.remove('hidden');
                groupedBtn.classList.add('bg-blue-600', 'text-white');
                groupedBtn.classList.remove('bg-gray-200');
                tableBtn.classList.add('bg-gray-200');
                tableBtn.classList.remove('bg-blue-600', 'text-white');
            }

            // Reapply filters after view change
            applyFilters();
        }

        // Client-side filtering
        const searchInput = document.getElementById('searchInput');
        const clientFilter = document.getElementById('clientFilter');
        const statutFilter = document.getElementById('statutFilter');
        const loadingSpinner = document.getElementById('loadingSpinner');
        const tableBody = document.getElementById('tableBody');
        const tableRows = Array.from(tableBody.querySelectorAll('tr[data-id]'));
        const groupedTables = Array.from(document.querySelectorAll('.client-body'));

        function applyFilters() {
            const query = searchInput.value.toLowerCase().trim();
            const clientId = clientFilter.value;
            const statut = statutFilter.value;
            loadingSpinner.classList.remove('hidden');

            setTimeout(() => {
                // Filter table view
                tableRows.forEach(row => {
                    const description = row.dataset.description.toLowerCase();
                    const client = row.dataset.client.toLowerCase();
                    const trajet = row.dataset.trajet.toLowerCase();
                    const lieu_livraison = row.dataset.lieu_livraison.toLowerCase();
                    const rowStatut = row.dataset.statut.toLowerCase();
                    const rowClientId = row.dataset.client_id;

                    const matchesSearch = !query || description.includes(query) || client.includes(query) || trajet.includes(query) || lieu_livraison.includes(query) || rowStatut.includes(query);
                    const matchesClient = !clientId || rowClientId === clientId;
                    const matchesStatut = !statut || rowStatut === statut;

                    row.style.display = matchesSearch && matchesClient && matchesStatut ? '' : 'none';
                });

                // Filter grouped view
                groupedTables.forEach(table => {
                    const rows = Array.from(table.querySelectorAll('tr[data-id]'));
                    const clientIdTable = table.dataset.client_id;
                    rows.forEach(row => {
                        const description = row.dataset.description.toLowerCase();
                        const trajet = row.dataset.trajet.toLowerCase();
                        const rowStatut = row.dataset.statut.toLowerCase();

                        const matchesSearch = !query || description.includes(query) || trajet.includes(query) || rowStatut.includes(query);
                        const matchesClient = !clientId || clientIdTable === clientId;
                        const matchesStatut = !statut || rowStatut === statut;

                        row.style.display = matchesSearch && matchesClient && matchesStatut ? '' : 'none';
                    });

                    // Hide client section if no rows are visible
                    const parentDiv = table.closest('div');
                    parentDiv.style.display = rows.some(row => row.style.display !== 'none') ? '' : 'none';
                });

                // Show "no results" message if needed
                const visibleTableRows = tableRows.filter(row => row.style.display !== 'none');
                const visibleGroupedRows = groupedTables.some(table => table.closest('div').style.display !== 'none');
                if (document.getElementById('tableView').classList.contains('hidden')) {
                    if (!visibleGroupedRows && !document.querySelector('.no-results-grouped')) {
                        const noResultsDiv = document.createElement('div');
                        noResultsDiv.className = 'no-results-grouped text-center py-6 text-gray-500';
                        noResultsDiv.textContent = 'Aucune marchandise trouvée.';
                        document.getElementById('groupedView').appendChild(noResultsDiv);
                    } else if (visibleGroupedRows) {
                        const noResultsDiv = document.querySelector('.no-results-grouped');
                        if (noResultsDiv) noResultsDiv.remove();
                    }
                } else {
                    if (visibleTableRows.length === 0 && !tableBody.querySelector('.no-results')) {
                        const noResultsRow = document.createElement('tr');
                        noResultsRow.className = 'no-results';
                        noResultsRow.innerHTML = '<td colspan="9" class="text-center py-6 text-gray-500">Aucune marchandise trouvée.</td>';
                        tableBody.appendChild(noResultsRow);
                    } else if (visibleTableRows.length > 0) {
                        const noResultsRow = tableBody.querySelector('.no-results');
                        if (noResultsRow) noResultsRow.remove();
                    }
                }

                loadingSpinner.classList.add('hidden');
            }, 300);
        }

        // Debounced filtering
        searchInput.addEventListener('input', debounce(applyFilters, 500));
        clientFilter.addEventListener('change', applyFilters);
        statutFilter.addEventListener('change', applyFilters);

        // Sorting for table view
        function sortTable(sortKey, th) {
            const order = th.dataset.order === 'asc' ? 'desc' : 'asc';
            th.dataset.order = order;

            // Update sort indicators
            document.querySelectorAll('#marchandisesTable th').forEach(header => {
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

            tableRows.sort((a, b) => {
                let aValue = a.dataset[sortKey] || '';
                let bValue = b.dataset[sortKey] || '';

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

            tableBody.innerHTML = '';
            tableRows.forEach(row => tableBody.appendChild(row));
            applyFilters(); // Reapply filters after sorting
        }

        // Sorting for grouped view
        function sortGroupedTable(sortKey, th, clientId) {
            const order = th.dataset.order === 'asc' ? 'desc' : 'asc';
            th.dataset.order = order;

            // Update sort indicators for the specific table
            const table = document.querySelector(`table[data-client_id="${clientId}"]`);
            table.querySelectorAll('th').forEach(header => {
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

            const tbody = table.querySelector('.client-body');
            const rows = Array.from(tbody.querySelectorAll('tr[data-id]'));

            rows.sort((a, b) => {
                let aValue = a.dataset[sortKey] || '';
                let bValue = b.dataset[sortKey] || '';

                aValue = aValue.toLowerCase();
                bValue = bValue.toLowerCase();

                if (aValue < bValue) return order === 'asc' ? -1 : 1;
                if (aValue > bValue) return order === 'asc' ? 1 : -1;
                return 0;
            });

            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
            applyFilters(); // Reapply filters after sorting
        }

        // Highlight selected row
        let selectedRow = null;
        function highlightRow(row) {
            if (selectedRow) {
                selectedRow.classList.remove('bg-blue-100');
            }
            row.classList.add('bg-blue-100');
            selectedRow = row;
            row.focus();
        }

        // Delete modal handling
        let deleteId = null;
        function openDeleteModal(id) {
            deleteId = id;
            const deleteForm = document.getElementById('deleteForm');
            deleteForm.action = `{{ url('marchandises') }}/${id}`;
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                modal.querySelector('div').classList.remove('scale-95');
                modal.querySelector('div').classList.add('scale-100');
                modal.querySelector('button[aria-label="Annuler la suppression"]').focus();
            }, 10);
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.querySelector('div').classList.remove('scale-100');
            modal.querySelector('div').classList.add('scale-95');
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                deleteId = null;
                document.getElementById('deleteForm').action = '#';
                document.body.style.overflow = '';
            }, 300);
        }

        // Notifications Toastify
        function showSuccessMessage(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#10B981",
            }).showToast();
        }

        function showErrorMessage(message) {
            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                backgroundColor: "#EF4444",
            }).showToast();
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(e) {
            const visibleRows = Array.from(document.querySelectorAll('#tableView:not(.hidden) tr[data-id]:not([style*="display: none"]), #groupedView:not(.hidden) tr[data-id]:not([style*="display: none"])'));
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
                const editLink = selectedRow.querySelector('a[aria-label="Modifier la marchandise"]');
                if (editLink) window.location.href = editLink.href;
            } else if (e.key === 'Escape' && !document.getElementById('deleteModal').classList.contains('hidden')) {
                closeDeleteModal();
            }
        });

        // Close modal on outside click
        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });

        // Initialize
        toggleView('table');
        if (searchInput.value || clientFilter.value || statutFilter.value) {
            applyFilters();
        }
        @if (session('success'))
            showSuccessMessage("{{ session('success') }}");
        @endif
    </script>
</x-layouts.app> --}}
