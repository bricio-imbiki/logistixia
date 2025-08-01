<x-layouts.app>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">{{ isset($marchandise) ? 'Modifier Marchandise' : 'Nouvelle Marchandise' }}</h1>
            <a href="{{ route('marchandises.index') }}"
               class="text-gray-600 hover:text-gray-800 font-medium flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Retour
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 max-w-lg mx-auto">
            <form id="marchandise-form" action="{{ isset($marchandise) ? route('marchandises.update', $marchandise) : route('marchandises.store') }}"
                  method="POST" novalidate>
                @csrf
                @if (isset($marchandise))
                    @method('PUT')
                @endif

                <!-- Notification Area -->
                <div id="form-notification" class="hidden p-4 mb-4 rounded-lg text-sm"></div>

                <!-- Nom -->
                <div class="mb-5">
                    <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                    <input type="text" id="nom" name="nom" value="{{ old('nom', $marchandise->nom ?? '') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ex. Riz blanc" required autofocus>
                    @error('nom')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Référence -->
                <div class="mb-5">
                    <label for="reference" class="block text-sm font-medium text-gray-700 mb-1">Référence</label>
                    <input type="text" id="reference" name="reference" value="{{ old('reference', $marchandise->reference ?? '') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ex. RIZ-001">
                    @error('reference')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Catégorie -->
                <div class="mb-5">
                    <label for="categorie" class="block text-sm font-medium text-gray-700 mb-1">Catégorie</label>
                    <input type="text" id="categorie" name="categorie" value="{{ old('categorie', $marchandise->categorie ?? '') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ex. Alimentaire">
                    @error('categorie')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Poids -->
                <div class="mb-5">
                    <label for="poids_moyen" class="block text-sm font-medium text-gray-700 mb-1">Poids moyen</label>
                    <input type="number" id="poids_moyen" name="poids_moyen" step="0.01"
                           value="{{ old('poids_moyen', $marchandise->poids_moyen ?? '') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ex. 1.5" min="0">
                    @error('poids_moyen')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Unité -->
                <div class="mb-5">
                    <label for="unite" class="block text-sm font-medium text-gray-700 mb-1">Unité</label>
                    <select id="unite" name="unite"
                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="" disabled {{ !old('unite', $marchandise->unite ?? '') ? 'selected' : '' }}>Choisir une unité</option>
                        <option value="kg" {{ old('unite', $marchandise->unite ?? '') === 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                        <option value="g" {{ old('unite', $marchandise->unite ?? '') === 'g' ? 'selected' : '' }}>Gramme (g)</option>
                        <option value="L" {{ old('unite', $marchandise->unite ?? '') === 'L' ? 'selected' : '' }}>Litre (L)</option>
                        <option value="unité" {{ old('unite', $marchandise->unite ?? '') === 'unité' ? 'selected' : '' }}>Unité</option>
                    </select>
                    @error('unite')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tarif -->
                <div class="mb-5">
                    <label for="tarif_par_defaut" class="block text-sm font-medium text-gray-700 mb-1">Tarif par défaut (Ar)</label>
                    <input type="number" id="tarif_par_defaut" name="tarif_par_defaut"
                           value="{{ old('tarif_par_defaut', $marchandise->tarif_par_defaut ?? '') }}"
                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Ex. 5000" min="0">
                    @error('tarif_par_defaut')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Buttons -->
                <div class="flex justify-end gap-3">
                    <a href="{{ route('marchandises.index') }}"
                       class="px-5 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium">
                        Annuler
                    </a>
                    <button type="submit" id="submit-btn"
                            class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium flex items-center gap-2">
                        <span id="submit-text">{{ isset($marchandise) ? 'Modifier' : 'Créer' }}</span>
                        <svg id="submit-spinner" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript for Client-Side Validation and Feedback -->
    <script>
  document.getElementById('marchandise-form').addEventListener('submit', async function(event) {
    event.preventDefault();
    const form = this;
    const notification = document.getElementById('form-notification');
    const submitBtn = document.getElementById('submit-btn');
    const submitText = document.getElementById('submit-text');
    const submitSpinner = document.getElementById('submit-spinner');

    // Reset notification
    notification.classList.add('hidden');

    // Validate inputs
    let errors = [];
    const nom = form.nom.value.trim();
    const poids = form.poids_moyen.value;
    const unite = form.unite.value;
    const tarif = form.tarif_par_defaut.value;

    if (!nom) errors.push('Le nom est requis.');
    if (poids && poids < 0) errors.push('Le poids ne peut pas être négatif.');
    if (!unite) errors.push('Veuillez sélectionner une unité.');
    if (tarif && tarif < 0) errors.push('Le tarif ne peut pas être négatif.');

    if (errors.length > 0) {
        notification.classList.remove('hidden', 'bg-green-50', 'text-green-700');
        notification.classList.add('bg-red-50', 'text-red-700');
        notification.innerHTML = errors.join('<br>');
        return;
    }

    // Show loading state
    submitBtn.disabled = true;
    submitText.classList.add('hidden');
    submitSpinner.classList.remove('hidden');

    try {
        const response = await fetch(form.action, {
            method: form.method === 'POST' ? 'POST' : 'PUT',
            body: new FormData(form),
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Accept': 'application/json',
            },
        });

        const data = await response.json();

        if (response.ok) {
            notification.classList.remove('hidden', 'bg-red-50', 'text-red-700');
            notification.classList.add('bg-green-50', 'text-green-700');
            notification.textContent = data.success;
            setTimeout(() => {
                window.location.href = data.redirect;
            }, 1000);
        } else {
            throw new Error(data.error || 'Erreur inconnue');
        }
    } catch (error) {
        notification.classList.remove('hidden', 'bg-green-50', 'text-green-700');
        notification.classList.add('bg-red-50', 'text-red-700');
        notification.textContent = error.message;
    } finally {
        submitBtn.disabled = false;
        submitText.classList.remove('hidden');
        submitSpinner.classList.add('hidden');
    }
});
    </script>
</x-layouts.app>
