<x-layouts.app>
    <div class="max-w-4xl mx-auto py-10 px-6 bg-white shadow rounded-lg">
        <h1 class="text-2xl font-bold mb-6">Suivi GPS des camions</h1>

        <div class="max-w-4xl mx-auto py-6">
            <div class="bg-white shadow rounded-lg p-6">
                <h2 class="text-xl font-semibold mb-4">Carte des Suivis GPS</h2>
                <p class="text-gray-600 mb-4">Visualisez la position actuelle de vos camions sur la carte.</p>

                <!-- Carte -->
                <div id="map" class="w-full h-96 rounded-lg"></div>
            </div>
        </div>
    </div>
    <script>
        const map = L.map('map').setView([-18.8792, 47.5079], 6); // Antananarivo par défaut

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        const suivis = @json($suivis);

        suivis.forEach(suivi => {
            const marker = L.marker([suivi.latitude, suivi.longitude]).addTo(map);
            marker.bindPopup(
                `<b>Camion:</b> ${suivi.camion?.immatriculation || 'Inconnu'}<br>
                 <b>Vitesse:</b> ${suivi.vitesse_kmh} km/h<br>
                 `
            );
        });
    </script>
</x-layouts.app>
