<?php

namespace App\Http\Controllers;

use App\Models\Marchandise;
use App\Models\Trajet;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MarchandiseController extends Controller
{
    protected function validateMarchandise(Request $request): array
    {
        return $request->validate([
            'trajet_id' => ['required', 'exists:trajets,id'],
            'client_id' => ['required', 'exists:clients,id'],
            'description' => ['nullable', 'string'],
            'poids_kg' => ['nullable', 'numeric', 'min:0'],
            'volume_m3' => ['nullable', 'numeric', 'min:0'],
            'valeur_estimee' => ['nullable', 'numeric', 'min:0'],
            'lieu_livraison' => ['nullable', 'string', 'max:120'],
            'statut' => ['required', 'in:chargee,en_transit,livree,retour'],
        ]);
    }

    public function index(Request $request)
    {
        $query = Marchandise::with(['trajet', 'client']);

        if ($search = $request->input('search')) {
            $query->where('description', 'like', "%{$search}%");
        }

        $marchandises = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('marchandises.index', compact('marchandises'));
    }

    public function create()
{
    return view('marchandises.form', [
        'marchandise' => null, // Add this to avoid undefined variable
        'trajets' => Trajet::all(),
        'clients' => Client::all(),
    ]);
}

    public function store(Request $request)
    {
        $validated = $this->validateMarchandise($request);

        try {
            Marchandise::create($validated);
            return redirect()->route('marchandises.index')->with('success', 'Marchandise ajoutée avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de l’ajout de marchandise : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout.']);
        }
    }

    public function edit(Marchandise $marchandise)
{
    $clients = Client::all();
    $trajets = Trajet::all();

    return view('marchandises.form', compact('marchandise', 'clients', 'trajets'));
}


    public function update(Request $request, Marchandise $marchandise)
    {

 $validated = $this->validateMarchandise($request);
        try {
            $marchandise->update($validated);
            return redirect()->route('marchandises.index')->with('success', 'Marchandise mise à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de marchandise : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy(Marchandise $marchandise)
    {
        try {
            $marchandise->delete();
            return redirect()->route('marchandises.index')->with('success', 'Marchandise supprimée avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de marchandise : ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}
