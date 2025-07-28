<x-layouts.app>
    @if (session('success'))
        <div class="mt-4 px-4 py-2 bg-green-100 text-green-800 rounded shadow max-w-7xl mx-auto">
            {{ session('success') }}
        </div>
    @endif

    <div class="max-w-7xl mx-auto p-6 bg-white rounded-xl shadow-md">

        <!-- Titre + Rafraîchir -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
               <x-heroicon-o-map-pin class="w-7 h-7 text-blue-600" />
                <span>Suivi GPS des camions</span>
            </h2>
            <button
                onclick="fetchAndUpdateMarkers(true)"
                class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold px-5 py-2 rounded-lg shadow transition"
                title="Rafraîchir la carte">
                <x-heroicon-o-arrow-path class="w-6 h-6" />
                Rafraîchir
            </button>
        </div>

        <!-- Recherche -->
        <form class="mb-4 max-w-md">
            <input type="text" name="search"
                   placeholder="🔍 Rechercher un camion par immatriculation..."
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-300 focus:outline-none" />
        </form>

        <!-- Carte -->
        <div id="map" class="w-full h-[550px] rounded-xl shadow-inner border border-gray-200"></div>
    </div>

   <script>
    const map = L.map('map').setView([-18.1492, 49.4023], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    const markers = {};
    let allSuivis = @json($suivis);

    function createOrUpdateMarker(suivi) {
        const key = suivi.camion?.id || suivi.id;
        const latLng = [suivi.latitude, suivi.longitude];

        if (markers[key]) {
            markers[key].setLatLng(latLng);
        } else {
            const marker = L.marker(latLng).addTo(map); // icône par défaut
            marker.bindPopup(`
                <div class="text-sm">
                    <strong>Camion:</strong> ${suivi.camion?.matricule || 'Inconnu'}<br>
                    <strong>Vitesse:</strong> ${suivi.vitesse_kmh} km/h<br>
                    <strong>Heure:</strong> ${new Date(suivi.event_time).toLocaleString()}
                </div>
            `);
            markers[key] = marker;
        }
    }

    function updateMarkers(suivisList) {
        suivisList.forEach(createOrUpdateMarker);
    }

    async function fetchAndUpdateMarkers(forceZoom = false) {
        try {
            const response = await fetch('{{ route("fetchLatest") }}');
            const data = await response.json();
            allSuivis = data;
            updateMarkers(data);
            if (forceZoom) zoomToVisibleMarkers();
        } catch (error) {
            console.error("Erreur de chargement GPS:", error);
        }
    }

    function zoomToVisibleMarkers() {
        const visible = Object.values(markers).filter(m => map.hasLayer(m));
        if (visible.length === 1) {
            map.setView(visible[0].getLatLng(), 16);
        } else if (visible.length > 1) {
            const bounds = L.latLngBounds(visible.map(m => m.getLatLng()));
            map.fitBounds(bounds, { padding: [30, 30] });
        }
    }

    // Recherche dynamique
    const searchInput = document.querySelector('input[name="search"]');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const term = searchInput.value.trim().toLowerCase();
            const matches = [];

            Object.entries(markers).forEach(([key, marker]) => {
                const suivi = allSuivis.find(s => (s.camion?.id || s.id) == key);
                const immatricule = suivi?.camion?.matricule?.toLowerCase() || '';

                if (immatricule.includes(term)) {
                    map.addLayer(marker);
                    matches.push(marker.getLatLng());
                } else {
                    map.removeLayer(marker);
                }
            });

            if (term.length === 0) {
                Object.values(markers).forEach(marker => map.addLayer(marker));
                zoomToVisibleMarkers();
            } else if (matches.length > 0) {
                zoomToVisibleMarkers();
            }
        });
    }

    // Initialisation
    updateMarkers(allSuivis);
    zoomToVisibleMarkers();
    setInterval(fetchAndUpdateMarkers, 15000);
</script>

</x-layouts.app>




       {{-- <script>
        // Centre la carte sur Toamasina
        const map = L.map('map').setView([-18.1492, 49.4023], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" class="text-blue-500">OpenStreetMap</a> contributors'
        }).addTo(map);

        const suivis = @json($suivis);

        suivis.forEach(suivi => {
            const marker = L.marker([suivi.latitude, suivi.longitude]).addTo(map);
            marker.bindPopup(`
                <div class="text-sm">
                    <strong>Camion ID:</strong> ${suivi.camion_id ?? 'Inconnu'}<br>
                    <strong>Vitesse:</strong> ${suivi.vitesse_kmh} km/h<br>
                    <strong>Heure:</strong> ${new Date(suivi.event_time).toLocaleString()}
                </div>
            `);
        });
    </script> --}}
