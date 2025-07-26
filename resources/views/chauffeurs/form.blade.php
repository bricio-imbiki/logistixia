<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($chauffeur) ? "Modifier le chauffeur" : "Ajouter un nouveau chauffeur" }}
        </h1>

        <form method="POST"
              action="{{ isset($chauffeur) ? route('chauffeurs.update', $chauffeur) : route('chauffeurs.store') }}"
              enctype="multipart/form-data"
              novalidate>
            @csrf
            @if(isset($chauffeur))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Champs texte via composants --}}
                <x-forms.input name="nom" label="Nom " :value="old('nom', $chauffeur->nom ?? '')" placeholder="Entrez le nom" required />
                <x-forms.input name="prenom" label="Prénom" :value="old('prenom', $chauffeur->prenom ?? '')" placeholder="Entrez le prénom" />
                <x-forms.input name="date_naissance" label="Date de naissance" type="date" :value="old('date_naissance', $chauffeur->date_naissance ?? '')" />
                <x-forms.input name="telephone" label="Téléphone" :value="old('telephone', $chauffeur->telephone ?? '')" />
                <x-forms.input name="email" label="Email" type="email" :value="old('email', $chauffeur->email ?? '')" />
                <x-forms.input name="adresse" label="Adresse" :value="old('adresse', $chauffeur->adresse ?? '')" />
                <x-forms.input name="permis_num" label="Numéro de permis" :value="old('permis_num', $chauffeur->permis_num ?? '')" />
                <x-forms.input name="permis_categorie" label="Catégorie permis" :value="old('permis_categorie', $chauffeur->permis_categorie ?? '')" />
                <x-forms.input name="permis_expire" label="Expiration permis" type="date" :value="old('permis_expire', $chauffeur->permis_expire ?? '')" />
                <x-forms.input name="date_embauche" label="Date d'embauche" type="date" :value="old('date_embauche', $chauffeur->date_embauche ?? '')" />
                <x-forms.input name="experience_annees" label="Années d'expérience" type="number" min="0" :value="old('experience_annees', $chauffeur->experience_annees ?? '')" />
                <x-forms.input name="cin_num" label="Numéro CIN" :value="old('cin_num', $chauffeur->cin_num ?? '')" />

                {{-- Statut --}}
                <x-forms.select
                    name="statut"
                    label="Statut"
                    :options="['titulaire' => 'Titulaire', 'remplacant' => 'Remplaçant']"
                    :selected="old('statut', $chauffeur->statut ?? 'titulaire')" />

                {{-- Apte médicalement --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Apte médicalement</label>
                    <x-forms.radio
                        name="apte_medicalement"
                        :options="[1 => 'Oui', 0 => 'Non']"
                        :selected="old('apte_medicalement', $chauffeur->apte_medicalement ?? 1)" />
                    @error('apte_medicalement') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                {{-- Photo --}}
                <div class="md:col-span-2">
                    <label for="photo_url" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-image text-gray-500 me-1"></i> Photo du chauffeur
                    </label>
                    <input type="file" name="photo_url" id="photo_url" accept="image/*"
                        onchange="previewImage(event)"
                        class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-500">
                    @error('photo_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                    <img id="photo_preview"
                        src="{{ isset($chauffeur) && $chauffeur->photo_url ? asset('storage/' . $chauffeur->photo_url) : '' }}"
                        alt="Aperçu"
                        class="mt-2 h-24 object-contain rounded border {{ isset($chauffeur->photo_url) ? '' : 'hidden' }}">
                </div>

                {{-- Document permis --}}
                <div class="md:col-span-2">
                    <label for="document_permis" class="block text-sm font-medium text-gray-700 mb-1">
                        <i class="fas fa-file-alt text-gray-500 me-1"></i> Document du permis
                    </label>
                    <input type="file" name="document_permis" id="document_permis"
                        class="block w-full mt-1 rounded border-gray-300 shadow-sm focus:ring focus:ring-blue-500">
                    @error('document_permis') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                    @if (isset($chauffeur) && $chauffeur->document_permis)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $chauffeur->document_permis) }}" target="_blank"
                               class="text-sm text-blue-600 hover:underline">Voir document actuel</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Boutons --}}
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('chauffeurs.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>

                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($chauffeur))
                        <i class="fas fa-pen mr-1"></i> Mettre à jour
                    @else
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    @endif
                </x-button>
            </div>
        </form>
    </div>

    {{-- Preview JS --}}
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
</x-layouts.app>
