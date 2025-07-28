<x-layouts.app>
    <!-- Flash message -->
    @if (session('success'))
        <div class="mt-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow max-w-7xl mx-auto">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-7xl mx-auto p-6 bg-white rounded-xl shadow-md">

        <!-- Titre + Bouton Rafraîchir -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                <x-heroicon-o-map class="w-7 h-7 text-blue-600" />
                <span>Suivi GPS des camions</span>
            </h2>

            <button
                onclick="fetchAndUpdateMarkers()"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow transition"
                title="Rafraîchir la carte">
                <x-heroicon-o-arrow-path class="w-6 h-6" />
                Rafraîchir
            </button>
        </div>

        <!-- Barre de recherche -->
        <form method="GET" action="{{ route('suivisGps.index') }}" class="mb-4 max-w-md">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="🔍 Rechercher un camion par immatriculation..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:outline-none" />
        </form>

        <!-- Carte -->
        <div id="map" class="w-full h-[550px] rounded-xl shadow-inner border border-gray-200"></div>
    </div>

    <script>
        // Centre la carte sur Toamasina
        const map = L.map('map').setView([-18.1492, 49.4023], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" class="text-blue-500">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Stocke les marqueurs par camion pour mise à jour facile
        const markers = {};

        // Charge les données initiales depuis Blade
        const suivis = @json($suivis);

        function createOrUpdateMarker(suivi) {
            const key = suivi.camion?.id || suivi.id;
            const latLng = [suivi.latitude, suivi.longitude];

            if (markers[key]) {
                markers[key].setLatLng(latLng);
            } else {
                const marker = L.marker(latLng).addTo(map);
                marker.bindPopup(`
                    <div class="text-sm">
                        <strong>Camion:</strong> ${suivi.camion_id|| 'Inconnu'}<br>
                        <strong>Vitesse:</strong> ${suivi.vitesse_kmh} km/h<br>
                        <strong>Heure:</strong> ${new Date(suivi.event_time).toLocaleString()}
                    </div>
                `);
                markers[key] = marker;
            }
        }

        // Initialisation des marqueurs
        suivis.forEach(createOrUpdateMarker);

        // Fonction pour rafraîchir la carte (ex: via appel API)
        async function fetchAndUpdateMarkers() {
            try {
                const response = await fetch('{{ route("fetchLatest") }}');
                const data = await response.json();

                data.forEach(createOrUpdateMarker);
            } catch (error) {
                console.error("Erreur lors du chargement des données GPS:", error);
            }
        }

        // Optionnel : rafraîchissement automatique toutes les 15s
        setInterval(fetchAndUpdateMarkers, 10000);
    </script>
</x-layouts.app>
