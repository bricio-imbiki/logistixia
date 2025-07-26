<x-layouts.app>
    <div class="max-w-4xl mx-auto p-6 bg-white rounded-xl shadow-md">
        <h1 class="text-2xl font-bold mb-6">
            {{ isset($client) ? "Modifier le client" : "Ajouter un nouveau client" }}
        </h1>

        <form method="POST"
              action="{{ isset($client) ? route('clients.update', $client) : route('clients.store') }}"
              enctype="multipart/form-data"
              novalidate>
            @csrf
            @if(isset($client))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-forms.input
                    name="raison_sociale"
                    label="Raison sociale *"
                    placeholder="Ex: Société XYZ"
                    :value="old('raison_sociale', $client->raison_sociale ?? '')"
                    required />

                <x-forms.input
                    name="client"
                    label="Nom du client"
                    placeholder="Ex: Jean Dupont"
                    :value="old('contact', $client->contact ?? '')" />

                <x-forms.input
                    name="telephone"
                    label="Téléphone"
                    placeholder="Ex: +212 6 12 34 56 78"
                    :value="old('telephone', $client->telephone ?? '')" />

                <x-forms.input
                    name="email"
                    label="Email"
                    type="email"
                    placeholder="Ex: contact@example.com"
                    :value="old('email', $client->email ?? '')" />

                <x-forms.input
                    name="adresse"
                    label="Adresse"
                    placeholder="Ex: 123 Rue de Casablanca"
                    :value="old('adresse', $client->adresse ?? '')" />

                <!-- Type de client -->
                <div>
                    <x-forms.select
                        name="type_client"
                        label="Type de client"
                        :options="[
                            'industriel' => 'Industriel',
                            'commercial' => 'Commercial',
                            'particulier' => 'Particulier'
                        ]"
                        :selected="old('type_client', $client->type_client ?? '')" />
                    @error('type_client')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Fichier justificatif -->
                {{-- <div class="md:col-span-2">
                    <label for="document_justificatif" class="block text-sm font-medium text-gray-700">
                        Document justificatif (optionnel)
                    </label>
                    <input type="file" name="document_justificatif" id="document_justificatif"
                        class="mt-1 block w-full border border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-500">
                    @error('document_justificatif')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror

                    @if (isset($client) && $client->document_justificatif)
                        <div class="mt-2">
                            <a href="{{ asset('storage/' . $client->document_justificatif) }}" target="_blank"
                               class="text-sm text-blue-600 hover:underline">Voir document actuel</a>
                        </div>
                    @endif
                </div> --}}
            </div>

              <!-- Boutons -->
                  <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('clients.index') }}"
                   class="px-4 py-2 rounded border border-gray-300 text-gray-600 hover:bg-gray-100">
                    <i class="fas fa-arrow-left mr-1"></i> Annuler
                </a>

                <x-button type="submit"
                          class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700 flex items-center gap-2">
                    @if(isset($client))
                        <i class="fas fa-pen mr-1"></i> Mettre à jour
                    @else
                        <i class="fas fa-save mr-1"></i> Enregistrer
                    @endif
                </x-button>
            </div>
        </form>
    </div>
</x-layouts.app>
