<x-layouts.app>
    <div class="max-w-3xl mx-auto p-6 bg-white rounded-xl shadow-md">

        <h1 class="text-2xl font-bold mb-6">
            {{ isset($remorque) ? "Modifier la remorque" : "Ajouter une nouvelle remorque" }}
        </h1>

        <form method="POST"
              action="{{ isset($remorque) ? route('remorques.update', $remorque) : route('remorques.store') }}"
              enctype="multipart/form-data"
              novalidate>
            @csrf
            @if(isset($remorque))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <!-- Matricule -->
                <div>
                    <label for="matricule" class="block text-sm font-medium text-gray-700">Matricule *</label>
                    <x-forms.input name="matricule" id="matricule"
                        :value="old('matricule', $remorque->matricule ?? '')"
                        placeholder="Entrez le matricule" required />
                    @error('matricule') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <x-forms.input name="type" id="type"
                        :value="old('type', $remorque->type ?? '')"
                        placeholder="Entrez le type" />
                    @error('type') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Capacité max -->
                <div>
                    <label for="capacite_max" class="block text-sm font-medium text-gray-700">Capacité Max (T)</label>
                    <x-forms.input name="capacite_max" id="capacite_max" type="number" step="0.01"
                        :value="old('capacite_max', $remorque->capacite_max ?? '')"
                        placeholder="Ex: 10.5" />
                    @error('capacite_max') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Camion -->
                <div>
                    <label for="camion_id" class="block text-sm font-medium text-gray-700">Camion associé</label>
                    <select name="camion_id" id="camion_id"
                            class="w-full mt-1 border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-500">
                        <option value="">-- Aucun --</option>
                        @foreach ($camions as $camion)
                            <option value="{{ $camion->id }}" @selected(old('camion_id', $remorque->camion_id ?? '') == $camion->id)>
                                {{ $camion->matricule }}
                            </option>
                        @endforeach
                    </select>
                    @error('camion_id') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Type de propriété -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type de propriété</label>
                    <x-forms.radio name="est_interne"
                        :options="[1 => 'Interne', 0 => 'Externe / Loué']"
                        :selected="old('est_interne', $remorque->est_interne ?? 1)" />
                    @error('est_interne') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Société propriétaire -->
                <div class="md:col-span-2">
                    <label for="societe_proprietaire" class="block text-sm font-medium text-gray-700">Société propriétaire</label>
                    <x-forms.input name="societe_proprietaire" id="societe_proprietaire"
                        :value="old('societe_proprietaire', $remorque->societe_proprietaire ?? '')"
                        placeholder="Nom de la société propriétaire" />
                    @error('societe_proprietaire') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Photo -->
                <div class="md:col-span-2">
                    <label for="photo_url" class="block text-sm font-medium text-gray-700">Photo de la remorque</label>
                    <input type="file" name="photo_url" id="photo_url" accept="image/*"
                           onchange="previewImage(event)"
                           class="mt-1 block w-full border border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-500">
                    @error('photo_url') <p class="text-red-600 text-xs mt-1">{{ $message }}</p> @enderror

                    <img id="photo_preview"
                         src="{{ isset($remorque) && $remorque->photo_url ? asset('storage/' . $remorque->photo_url) : '' }}"
                         alt="Aperçu de l'image"
                         class="mt-2 h-24 object-contain border rounded {{ isset($remorque) && $remorque->photo_url ? '' : 'hidden' }}">
                </div>

            </div>

            <!-- Boutons -->
            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('remorques.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>

                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($remorque))
                        <i class="fas fa-pen mr-1"></i> Mettre à jour
                    @else
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    @endif
                </x-button>
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
