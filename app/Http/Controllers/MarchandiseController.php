<?php

namespace App\Http\Controllers;

use App\Models\Marchandise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MarchandiseController extends Controller
{
    /**
     * Validate request data for creating or updating a Marchandise.
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
     * Display a listing of the resource.
     */
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateMarchandise($request);
        try {
            $marchandise = Marchandise::create($validated);
            return response()->json($marchandise, 200);
        } catch (Exception $e) {
            Log::error('Erreur lors de l’enregistrement : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur lors de l’enregistrement'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Marchandise $marchandise)
    {
        return view('marchandises.form', compact('marchandise'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marchandise $marchandise)
    {
        $validated = $this->validateMarchandise($request);
        try {
            $marchandise->update($validated);
            return response()->json($marchandise, 200);
        } catch (Exception $e) {
            Log::error('Erreur de mise à jour : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur de mise à jour'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Marchandise $marchandise)
    {
        try {
            $marchandise->delete();
            return response()->json(['message' => 'Marchandise supprimée.'], 200);
        } catch (Exception $e) {
            Log::error('Erreur de suppression : ' . $e->getMessage());
            return response()->json(['message' => 'Erreur de suppression'], 500);
        }
    }
}
