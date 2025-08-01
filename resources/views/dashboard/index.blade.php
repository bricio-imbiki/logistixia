<x-layouts.app>
    <!-- Dependencies -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tippy.js@6/dist/tippy.css" />
    <script src="https://cdn.jsdelivr.net/npm/tippy.js@6"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.5/leaflet.awesome-markers.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.5/leaflet.awesome-markers.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
        <!-- Header -->
        <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tableau de bord') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Aperçu de vos opérations logistiques') }}</p>
            </div>
            <div class="relative">
                <button id="dateRangeBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2" aria-label="Sélectionner une période">
                    <x-heroicon-o-calendar class="h-5 w-5" aria-hidden="true" />
                    <span id="dateRangeText">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
                </button>
                <input type="text" id="dateRangePicker" class="hidden" value="{{ $startDate }} to {{ $endDate }}"
                       aria-label="Sélectionner une plage de dates">
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transform transition-transform hover:scale-105"
                 data-tippy-content="Nombre total de camions dans votre flotte">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Camions') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1" id="totalCamions">{{ $stats['total_camions'] }}</p>
                        <p class="text-xs text-blue-500 flex items-center mt-1">
                            <x-heroicon-o-truck class="h-4 w-4 mr-1" />
                            {{ $stats['active_trucks'] }} {{ __('actifs') }}
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <x-heroicon-o-truck class="h-6 w-6 text-blue-500 dark:text-blue-300" />
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transform transition-transform hover:scale-105"
                 data-tippy-content="Nombre total de chauffeurs employés">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Chauffeurs') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1" id="totalChauffeurs">{{ $stats['total_chauffeurs'] }}</p>
                        <p class="text-xs text-orange-500 flex items-center mt-1">
                            <x-heroicon-o-user class="h-4 w-4 mr-1" />
                            {{ $stats['active_drivers'] }} {{ __('actifs') }}
                        </p>
                    </div>
                    <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                        <x-heroicon-o-user class="h-6 w-6 text-orange-500 dark:text-orange-300" />
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transform transition-transform hover:scale-105"
                 data-tippy-content="Revenus totaux encaissés des livraisons sur la période sélectionnée">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Revenus') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1" id="totalRevenue">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} Ar</p>
                        <p class="text-xs text-green-500 flex items-center mt-1">
                            <x-heroicon-o-arrow-up class="h-4 w-4 mr-1" />
                            {{ $stats['trips_by_status']['livree'] }} {{ __('livraisons') }}
                        </p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <x-heroicon-o-currency-dollar class="h-6 w-6 text-green-500 dark:text-green-300" />
                    </div>
                </div>
            </div>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transform transition-transform hover:scale-105"
                 data-tippy-content="Dépenses totales sur la période sélectionnée">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Dépenses') }}</p>
                        <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1" id="totalDepenses">{{ number_format($stats['total_depenses'], 0, ',', ' ') }} Ar</p>
                        <p class="text-xs text-red-500 flex items-center mt-1">
                            <x-heroicon-o-arrow-down class="h-4 w-4 mr-1" />
                            {{ $stats['total_trips'] }} {{ __('trajets') }}
                        </p>
                    </div>
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        <x-heroicon-o-arrow-trending-down class="h-6 w-6 text-purple-500 dark:text-purple-300" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts, Map, and Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 flex-1">
            <!-- Revenue Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 lg:col-span-2 h-[400px] flex flex-col">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Tendance des revenus') }}</h2>
                <div class="flex-1 relative">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>

            <!-- Trip Status Chart -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 h-[400px] flex flex-col">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Statut des marchandises') }}</h2>
                <div class="flex-1 relative">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <!-- Map Integration -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 lg:col-span-2 h-[400px] flex flex-col">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Localisation des camions') }}</h2>
                <div id="truckMap" class="flex-1"></div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 h-[400px] flex flex-col">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Activités récentes') }}</h2>
                    <select id="activityFilter" class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-sm" aria-label="Filtrer les activités">
                        <option value="all">{{ __('Toutes') }}</option>
                        <option value="livree">{{ __('Livrée') }}</option>
                        <option value="en_transit">{{ __('En transit') }}</option>
                        <option value="chargee">{{ __('Chargée') }}</option>
                        <option value="retour">{{ __('Retour') }}</option>
                    </select>
                </div>
                <div id="activityFeed" class="flex-1 space-y-4 overflow-y-auto pr-2">
                    @forelse ($recent_activities as $activity)
                        <div class="flex items-start gap-3 border-b border-gray-200 dark:border-gray-700 pb-3 animate-slide-in" style="animation-delay: {{ $loop->index * 0.1 }}s" role="listitem" tabindex="0">
                            <div class="bg-indigo-50 dark:bg-indigo-900/50 p-2 rounded-full">
                                <i class="fas fa-clipboard-list h-5 w-5 text-indigo-500 dark:text-indigo-300"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->itineraire?->lieu_depart ?? '-' }} → {{ $activity->itineraire?->lieu_arrivee ?? '-' }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity->chauffeur?->nom ?? '-' }} | {{ $activity->camion?->accreditation ?? '-' }} | {{ \Carbon\Carbon::parse($activity->date_depart)->format('d/m/Y') }}</p>
                                <p class="text-xs mt-1">
                                  @php
    $statut = $activity->transport?->statut;

    $statutClass = match ($statut) {
        'livree' => 'text-green-600 dark:text-green-400',
        'en_transit' => 'text-yellow-600 dark:text-yellow-400',
        'retour' => 'text-red-600 dark:text-red-400',
        default => 'text-indigo-600 dark:text-indigo-400',
    };
@endphp

Statut: <span class="{{ $statutClass }}">{{ ucfirst($statut ?? '-') }}</span>
                                </p>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400 text-center text-sm mt-4">{{ __('Aucune activité récente') }}</p>
                    @endforelse
                </div>
                <button id="loadMore" class="mt-4 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 text-sm">{{ __('Charger plus') }}</button>
            </div>
        </div>

        <!-- Loading Spinner -->
        <div id="loadingSpinner" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
            <svg class="w-8 h-8 animate-spin text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    <style>
        @keyframes slideIn { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }
        .animate-slide-in { animation: slideIn 0.3s ease-out forwards; }
        #activityFeed::-webkit-scrollbar { width: 6px; }
        #activityFeed::-webkit-scrollbar-track { background: transparent; }
        #activityFeed::-webkit-scrollbar-thumb { background: #9CA3AF; border-radius: 3px; }
        #activityFeed::-webkit-scrollbar-thumb:hover { background: #6B7280; }
        canvas { max-height: 100% !important; width: 100% !important; }
        [data-tippy-root] { font-size: 0.875rem; border-radius: 0.375rem; }
        .dark [data-tippy-root] { background-color: #1F2937; }
        #truckMap { border-radius: 8px; }
    </style>

    <script>
        // Status class mapping
        const statusClasses = {
            'livree': 'text-green-600 dark:text-green-400',
            'en_transit': 'text-yellow-600 dark:text-yellow-400',
            'retour': 'text-red-600 dark:text-red-400',
            'chargee': 'text-indigo-600 dark:text-indigo-400',
            'default': 'text-indigo-600 dark:text-indigo-400'
        };

        // Initialize Flatpickr
        flatpickr('#dateRangePicker', {
            mode: 'range',
            dateFormat: 'Y-m-d',
            defaultDate: ['{{ $startDate }}', '{{ $endDate }}'],
            onChange: function(selectedDates) {
                if (selectedDates.length === 2) {
                    const start = selectedDates[0].toLocaleDateString('fr-FR');
                    const end = selectedDates[1].toLocaleDateString('fr-FR');
                    document.getElementById('dateRangeText').textContent = `${start} - ${end}`;
                    fetchData(selectedDates[0].toISOString().split('T')[0], selectedDates[1].toISOString().split('T')[0]);
                }
            }
        });

        document.getElementById('dateRangeBtn').addEventListener('click', () => {
            document.getElementById('dateRangePicker')._flatpickr.open();
        });

        // Fetch Dashboard Data
        async function fetchData(startDate, endDate, filter = 'all') {
            document.getElementById('loadingSpinner').classList.remove('hidden');
            try {
                const response = await fetch(`{{ route('dashboard') }}?start_date=${startDate}&end_date=${endDate}&filter=${filter}`, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const data = await response.json();
                updateDashboard(data);
                updateMap(data.trucks);
            } catch (error) {
                Toastify({ text: 'Erreur lors du chargement', duration: 3000, backgroundColor: '#EF4444' }).showToast();
            } finally {
                document.getElementById('loadingSpinner').classList.add('hidden');
            }
        }

        // Update Dashboard
        function updateDashboard(data) {
            document.getElementById('totalCamions').textContent = data.stats.total_camions;
            document.getElementById('totalChauffeurs').textContent = data.stats.total_chauffeurs;
            document.getElementById('totalRevenue').textContent = new Intl.NumberFormat('fr-FR').format(data.stats.total_revenue) + ' Ar';
            document.getElementById('totalDepenses').textContent = new Intl.NumberFormat('fr-FR').format(data.stats.total_depenses) + ' Ar';

            revenueChart.data.labels = data.chart_data.labels;
            revenueChart.data.datasets[0].data = data.chart_data.revenue;
            revenueChart.update();

            statusChart.data.datasets[0].data = [
                data.stats.trips_by_status.chargee,
                data.stats.trips_by_status.en_transit,
                data.stats.trips_by_status.livree,
                data.stats.trips_by_status.retour
            ];
            statusChart.update();

            const activityFeed = document.getElementById('activityFeed');
            activityFeed.innerHTML = data.recent_activities.map((a, i) => {
                const status = a.transport[0]?.statut ?? 'default';
                const className = statusClasses[status] || statusClasses['default'];
                return `
                    <div class="flex items-start gap-3 border-b border-gray-200 dark:border-gray-700 pb-3 animate-slide-in" style="animation-delay: ${i * 0.1}s" role="listitem" tabindex="0">
                        <div class="bg-indigo-50 dark:bg-indigo-900/50 p-2 rounded-full">
                            <i class="fas fa-clipboard-list h-5 w-5 text-indigo-500 dark:text-indigo-300"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">${a.itineraire?.lieu_depart ?? '-'} → ${a.itineraire?.lieu_arrivee ?? '-'}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${a.chauffeur?.nom ?? '-'} | ${a.camion?.accreditation ?? '-'} | ${new Date(a.date_depart).toLocaleDateString('fr-FR')}</p>
                            <p class="text-xs">Statut: <span class="${className}">${a.transport[0]?.statut ?? '-'}</span></p>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Charts Initialization
        const revenueChart = new Chart(document.getElementById('revenueChart'), {
            type: 'line',
            data: {
                labels: @json($chart_data['labels']),
                datasets: [{
                    label: 'Revenus (Ar)',
                    data: @json($chart_data['revenue']),
                    borderColor: '#4F46E5',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        const statusChart = new Chart(document.getElementById('statusChart'), {
            type: 'doughnut',
            data: {
                labels: ['Chargée', 'En transit', 'Livrée', 'Retour'],
                datasets: [{
                    data: [
                        {{ $stats['trips_by_status']['chargee'] }},
                        {{ $stats['trips_by_status']['en_transit'] }},
                        {{ $stats['trips_by_status']['livree'] }},
                        {{ $stats['trips_by_status']['retour'] }}
                    ],
                    backgroundColor: ['#4F46E5', '#F59E0B', '#10B981', '#EF4444']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Map Initialization
        const map = L.map('truckMap').setView([-18.1492, 49.40234], 12); // Center on Toamasina, Madagascar
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let truckMarkers = [];

        function updateMap(trucks) {
            // Clear existing markers
            truckMarkers.forEach(marker => map.removeLayer(marker));
            truckMarkers = [];

            // Add new markers for trucks
            trucks.forEach(truck => {
                const marker = L.AwesomeMarkers.icon({
                    icon: 'truck',
                    prefix: 'fa',
                    markerColor: truck.status === 'en_transit' ? 'blue' : truck.status === 'livree' ? 'green' : truck.status === 'chargee' ? 'purple' : 'red'
                });

                const truckMarker = L.marker([truck.latitude, truck.longitude], { icon: marker })
                    .addTo(map)
                    .bindPopup(`
                        <b>Camion: ${truck.accreditation}</b><br>
                        Chauffeur: ${truck.chauffeur?.nom ?? '-'}<br>
                        Statut: ${truck.status}<br>
                        Dernière mise à jour: ${new Date(truck.last_updated).toLocaleString('fr-FR')}
                    `);
                truckMarkers.push(truckMarker);
            });

            // Adjust map bounds to fit all markers if there are any
            if (truckMarkers.length > 0) {
                const group = new L.featureGroup(truckMarkers);
                map.fitBounds(group.getBounds(), { padding: [50, 50] });
            }
        }

        // Initial map update
        updateMap(@json($trucks));

        // Tooltips
        tippy('[data-tippy-content]', { theme: 'light', animation: 'scale' });

        // Activity Filter
        document.getElementById('activityFilter').addEventListener('change', (e) => {
            const filter = e.target.value;
            fetchData('{{ $startDate }}', '{{ $endDate }}', filter);
        });

        // Load More
        document.getElementById('loadMore').addEventListener('click', () => {
            Toastify({ text: 'Chargement des activités supplémentaires', duration: 2000, backgroundColor: '#4F46E5' }).showToast();
            // Add pagination logic here
        });
    </script>
</x-layouts.app>
