<form method="POST"
      action="{{ isset($camion) ? route('camions.update', $camion) : route('camions.store') }}"
      enctype="multipart/form-data">
    @csrf
    @if(isset($camion))
        @method('PUT')
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Matricule -->
        <div>
            <label for="matricule" class="block text-sm font-medium text-gray-700">Matricule *</label>
            <x-forms.input name="matricule" :value="old('matricule', $camion->matricule ?? '')" required />
        </div>

        <!-- Marque -->
        <div>
            <label for="marque" class="block text-sm font-medium text-gray-700">Marque</label>
            <x-forms.input name="marque" :value="old('marque', $camion->marque ?? '')" />
        </div>

        <!-- Modèle -->
        <div>
            <label for="modele" class="block text-sm font-medium text-gray-700">Modèle</label>
            <x-forms.input name="modele" :value="old('modele', $camion->modele ?? '')" />
        </div>

        <!-- Année -->
        <div>
            <label for="annee" class="block text-sm font-medium text-gray-700">Année</label>
            <x-forms.input name="annee" type="number" max="{{ date('Y') }}"
                           :value="old('annee', $camion->annee ?? '')" />
        </div>

        <!-- Capacité -->
        <div>
            <label for="capacite_kg" class="block text-sm font-medium text-gray-700">Capacité (kg)</label>
            <x-forms.input name="capacite_kg" type="number"
                           :value="old('capacite_kg', $camion->capacite_kg ?? '')" />
        </div>

        <!-- Statut -->
        <div>
            <label for="statut" class="block text-sm font-medium text-gray-700">Statut</label>
            <x-forms.select name="statut"
                :options="['disponible' => 'Disponible', 'en_mission' => 'En mission', 'panne' => 'Panne', 'maintenance' => 'Maintenance']"
                :selected="old('statut', $camion->statut ?? '')" />
        </div>

        <!-- Type de propriété -->
        <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-1">Type de propriété</label>
            <x-forms.radio name="est_interne"
                :options="[1 => 'Interne', 0 => 'Externe / Loué']"
                :selected="old('est_interne', $camion->est_interne ?? 1)" />
        </div>

        <!-- Société propriétaire -->
        <div class="md:col-span-2">
            <label for="societe_proprietaire" class="block text-sm font-medium text-gray-700">Société propriétaire</label>
            <x-forms.input name="societe_proprietaire"
                           :value="old('societe_proprietaire', $camion->societe_proprietaire ?? '')" />
        </div>

        <!-- Photo -->
        <div class="md:col-span-2">
            <label for="photo_url" class="block text-sm font-medium text-gray-700">Photo du camion</label>
            <input type="file" name="photo_url" id="photo_url" accept="image/*"
                   onchange="previewImage(event)"
                   class="mt-1 block w-full border border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-500">

            <img id="photo_preview"
                 src="{{ isset($camion) && $camion->photo_url ? asset('storage/' . $camion->photo_url) : '' }}"
                 alt="Aperçu de l'image"
                 class="mt-2 h-24 object-contain border rounded {{ isset($camion) && $camion->photo_url ? '' : 'hidden' }}">
        </div>
    </div>

    <!-- Boutons -->
    <div class="mt-6 flex justify-end gap-3">
        <a href="{{ route('camions.index') }}"
           class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
            Annuler
        </a>
        <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700">
            {{ isset($camion) ? 'Mettre à jour' : 'Enregistrer 🚚' }}
        </button>
    </div>
</form>

<script>
function previewImage(event) {
    const input = event.target;
    const preview = document.getElementById('photo_preview');

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
