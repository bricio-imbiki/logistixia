<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Chauffeur;
use App\Models\Marchandise;
use App\Models\Trajet;
use App\Models\Client;
use App\Models\Itineraire;
use App\Models\Remorque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MarchandiseController extends Controller
{
    protected function validateMarchandise(Request $request, bool $isMultiple = false): array
    {
        if ($isMultiple) {
            return $request->validate([
                'client_id' => ['required', 'exists:clients,id'],
                'marchandises' => ['required', 'array', 'min:1'],
                'marchandises.*.trajet_id' => ['required', 'exists:trajets,id'],
                'marchandises.*.description' => ['nullable', 'string'],
                'marchandises.*.poids_kg' => ['nullable', 'numeric', 'min:0'],
                'marchandises.*.volume_m3' => ['nullable', 'numeric', 'min:0'],
                'marchandises.*.valeur_estimee' => ['nullable', 'numeric', 'min:0'],
                'marchandises.*.lieu_livraison' => ['nullable', 'string', 'max:120'],
                'marchandises.*.statut' => ['required', 'in:chargee,en_transit,livree,retour'],
            ]);
        }

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
        $query = Marchandise::query();

        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%')
                  ->orWhere('lieu_livraison', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $marchandises = $query->with(['client', 'trajet'])->paginate(10);
        $clients = Client::with('marchandises.trajet')->get();
        $stats = [
            'chargee' => Marchandise::where('statut', 'chargee')->count(),
            'en_transit' => Marchandise::where('statut', 'en_transit')->count(),
            'livree' => Marchandise::where('statut', 'livree')->count(),
            'retour' => Marchandise::where('statut', 'retour')->count(),
        ];

        return view('marchandises.index', compact('marchandises', 'clients', 'stats'));
    }

    public function create()
    {
        return view('marchandises.form', [
            'marchandise' => null,
            'trajets' => Trajet::with('itineraire')->get(),
            'clients' => Client::all(),
            'camions' => Camion::all(),
            'remorques' => Remorque::all(),
            'chauffeurs' => Chauffeur::all(),
            'itineraires' => Itineraire::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateMarchandise($request, true);

        try {
            foreach ($validated['marchandises'] as $data) {
                Marchandise::create([
                    'trajet_id' => $data['trajet_id'],
                    'client_id' => $validated['client_id'],
                    'description' => $data['description'] ?? null,
                    'poids_kg' => $data['poids_kg'] ?? null,
                    'volume_m3' => $data['volume_m3'] ?? null,
                    'valeur_estimee' => $data['valeur_estimee'] ?? null,
                    'lieu_livraison' => $data['lieu_livraison'] ?? null,
                    'statut' => $data['statut'],
                ]);
            }
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Marchandises ajoutées avec succès.'
                ], 200);
            }
            return redirect()->route('marchandises.index')->with('success', 'Marchandises ajoutées avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de l’ajout de marchandise : ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de l’ajout : ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout.']);
        }
    }

    public function edit(Marchandise $marchandise)
    {
        $clients = Client::all();
        $trajets = Trajet::with('itineraire')->get();

        return view('marchandises.form', compact('marchandise', 'clients', 'trajets', 'camions', 'remorques', 'chauffeurs', 'itineraires'));
    }

    public function update(Request $request, Marchandise $marchandise)
    {
        $validated = $this->validateMarchandise($request);

        try {
            $marchandise->update($validated);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Marchandise mise à jour avec succès.'
                ], 200);
            }
            return redirect()->route('marchandises.index')->with('success', 'Marchandise mise à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour de marchandise : ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()
                ], 500);
            }
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy(Marchandise $marchandise, Request $request)
    {
        try {
            $marchandise->delete();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Marchandise supprimée avec succès.'
                ], 200);
            }
            return redirect()->route('marchandises.index')->with('success', 'Marchandise supprimée avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression de marchandise : ' . $e->getMessage());
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression : ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}
