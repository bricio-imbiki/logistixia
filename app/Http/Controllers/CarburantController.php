<?php

namespace App\Http\Controllers;

use App\Models\Carburant;
use App\Models\Camion;
use App\Models\Trajet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class CarburantController extends Controller
{
    protected function validateCarburant(Request $request): array
    {
        return $request->validate([
            'camion_id' => ['required', 'exists:camions,id'],
            'trajet_id' => ['nullable', 'exists:trajets,id'],
            'date_achat' => ['required', 'date'],
            'quantite_litres' => ['required', 'numeric', 'min:0'],
            'prix_total' => ['required', 'numeric', 'min:0'],
            'station' => ['nullable', 'string', 'max:100'],

        ]);
    }

    public function index(Request $request)
    {
        $query = Carburant::with(['camion', 'trajet']);

        if ($search = $request->input('search')) {
            $query->where('station', 'like', "%{$search}%");
        }

     $carburants = $query->orderByDesc('date_achat')->paginate(10);


        return view('carburants.index', compact('carburants'));
    }

    public function create()
    {
        return view('carburants.form', [
            'carburant' => null,
            'camions' => Camion::all(),
            'trajets' => Trajet::all(),
        ]);
    }

public function store(Request $request)
{
    $validated = $this->validateCarburant($request);

    // Calcul automatique si pas fourni
    if (!isset($validated['prix_unitaire']) && $validated['quantite_litres'] > 0) {
        $validated['prix_unitaire'] = $validated['prix_total'] / $validated['quantite_litres'];
    }

    try {
        Carburant::create($validated);
        return redirect()->route('carburants.index')->with('success', 'Carburant enregistré avec succès.');
    } catch (Exception $e) {
        Log::error('Erreur lors de l’enregistrement du carburant : ' . $e->getMessage());
        return back()->withInput()->withErrors(['error' => 'Erreur lors de l’enregistrement.']);
    }
}


    public function edit(Carburant $carburant)
    {
        return view('carburants.form', [
            'carburant' => $carburant,
            'camions' => Camion::all(),
            'trajets' => Trajet::all(),
        ]);
    }

    public function update(Request $request, Carburant $carburant)
    {
        $validated = $this->validateCarburant($request);

        try {
            $carburant->update($validated);
            return redirect()->route('carburants.index')->with('success', 'Carburant mis à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du carburant : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy(Carburant $carburant)
    {
        try {
            $carburant->delete();
            return redirect()->route('carburants.index')->with('success', 'Carburant supprimé avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du carburant : ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}
