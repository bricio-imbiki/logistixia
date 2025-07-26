<x-layouts.app>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-md">

        <h1 class="text-2xl font-bold mb-6">
            {{ isset($camion) ? "Modifier le camion " : "Ajouter un nouveau camion" }}
        </h1>

        <form method="POST"
              action="{{ isset($camion) ? route('camions.update', $camion) : route('camions.store') }}"
              enctype="multipart/form-data"
              novalidate>
            @csrf
            @if(isset($camion))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Matricule -->
                <div>
                    <label for="matricule" class="block text-sm font-medium text-gray-700">Matricule *</label>
                    <x-forms.input name="matricule" id="matricule"
                        :value="old('matricule', $camion->matricule ?? '')"
                        placeholder="Entrez le matricule" required />
                    @error('matricule') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Marque -->
                <div>
                    <label for="marque" class="block text-sm font-medium text-gray-700">Marque</label>
                    <x-forms.input name="marque" id="marque"
                        :value="old('marque', $camion->marque ?? '')"
                        placeholder="Entrez la marque" />
                    @error('marque') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Modèle -->
                <div>
                    <label for="modele" class="block text-sm font-medium text-gray-700">Modèle</label>
                    <x-forms.input name="modele" id="modele"
                        :value="old('modele', $camion->modele ?? '')"
                        placeholder="Entrez le modèle" />
                    @error('modele') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Année -->
                <div>
                    <label for="annee" class="block text-sm font-medium text-gray-700">Année</label>
                    <x-forms.input name="annee" id="annee" type="number" max="{{ date('Y') }}"
                        :value="old('annee', $camion->annee ?? '')"
                        placeholder="Ex: 2023" />
                    @error('annee') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Capacité -->
                <div>
                    <label for="capacite_kg" class="block text-sm font-medium text-gray-700">Capacité (kg)</label>
                    <x-forms.input name="capacite_kg" id="capacite_kg" type="number"
                        :value="old('capacite_kg', $camion->capacite_kg ?? '')"
                        placeholder="Entrez la capacité en kg" />
                    @error('capacite_kg') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Statut -->
                <div>

                    <x-forms.select name="statut" id="statut" label="Statut"
                        :options="[
                            'disponible' => 'Disponible',
                            'en_mission' => 'En mission',
                            'panne' => 'Panne',
                            'maintenance' => 'Maintenance',
                        ]"
                        :selected="old('statut', $camion->statut ?? '')" />
                    @error('statut') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Type de propriété -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de propriété</label>
                    <x-forms.radio name="est_interne"
                        :options="[1 => 'Interne', 0 => 'Externe / Loué']"
                        :selected="old('est_interne', $camion->est_interne ?? 1)" />
                    @error('est_interne') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Société propriétaire -->
                <div class="md:col-span-2">
                    <label for="societe_proprietaire" class="block text-sm font-medium text-gray-700">Société propriétaire</label>
                    <x-forms.input name="societe_proprietaire" id="societe_proprietaire"
                        :value="old('societe_proprietaire', $camion->societe_proprietaire ?? '')"
                        placeholder="Nom de la société propriétaire" />
                    @error('societe_proprietaire') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Photo -->
                <div class="md:col-span-2">
                    <label for="photo_url" class="block text-sm font-medium text-gray-700">Photo du camion</label>
                    <input type="file" name="photo_url" id="photo_url" accept="image/*"
                           onchange="previewImage(event)"
                           class="mt-1 block w-full border border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-500">
                    @error('photo_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

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
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>

                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($camion))
                        <i class="fas fa-pen mr-1"></i> Mettre à jour
                    @else
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    @endif
                </x-button>
            </div>
            </div>
        </form>
    </div>

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
