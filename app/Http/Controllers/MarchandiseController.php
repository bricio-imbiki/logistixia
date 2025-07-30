<?php

namespace App\Http\Controllers;

use App\Models\Marchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MarchandiseController extends Controller
{
    /**
     * Validation unique pour la création et la mise à jour
     */
    protected function validateMarchandise(Request $request): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:120'],
            'reference' => ['nullable', 'string', 'max:60'],
            'categorie' => ['nullable', 'string', 'max:60'],
            'unite' => ['nullable', 'string', 'max:30'],
            'poids_moyen' => ['nullable', 'numeric', 'min:0'],
            'tarif_par_defaut' => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    /**
     * Gestion DRY d'une action en try/catch avec callback
     */
    protected function safeExecute(callable $action, string $successMessage, string $errorMessage)
    {
        try {
            $action();
            return redirect()->route('marchandises.index')->with('success', $successMessage);
        } catch (Exception $e) {
            Log::error($errorMessage . ' : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => $errorMessage]);
        }
    }

    public function index(Request $request)
    {
        $marchandises = Marchandise::query()
            ->when($request->filled('search'), fn($q) =>
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('reference', 'like', '%' . $request->search . '%')
            )
            ->orderBy('nom')
            ->paginate(10);

        return view('marchandises.index', compact('marchandises'));
    }

    public function create()
    {
        return view('marchandises.form', ['marchandise' => null]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateMarchandise($request);

        return $this->safeExecute(
            fn() => Marchandise::create($validated),
            'Marchandise ajoutée avec succès.',
            'Erreur lors de l’enregistrement'
        );
    }

    public function edit(Marchandise $marchandise)
    {
        return view('marchandises.form', compact('marchandise'));
    }

    public function update(Request $request, Marchandise $marchandise)
    {
        $validated = $this->validateMarchandise($request);

        return $this->safeExecute(
            fn() => $marchandise->update($validated),
            'Marchandise mise à jour.',
            'Erreur de mise à jour'
        );
    }

    public function destroy(Marchandise $marchandise)
    {
        return $this->safeExecute(
            fn() => $marchandise->delete(),
            'Marchandise supprimée.',
            'Erreur de suppression'
        );
    }
}
