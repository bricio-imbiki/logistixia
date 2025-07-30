<?php

namespace App\Http\Controllers;

use App\Models\MarchandiseTransportee;
use App\Models\Marchandise;
use App\Models\Trajet;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class MarchandiseTransporteeController extends Controller
{
    protected function validateTransport(Request $request, bool $isMultiple = false): array
    {
        if ($isMultiple) {
            return $request->validate([
                'client_id' => ['required', 'exists:clients,id'],
                'marchandises' => ['required', 'array', 'min:1'],
                'marchandises.*.trajet_id' => ['required', 'exists:trajets,id'],
                'marchandises.*.marchandise_id' => ['required', 'exists:marchandises,id'],
                'marchandises.*.quantite' => ['required', 'numeric', 'min:0'],
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
            'marchandise_id' => ['required', 'exists:marchandises,id'],
            'quantite' => ['required', 'numeric', 'min:0'],
            'poids_kg' => ['nullable', 'numeric', 'min:0'],
            'volume_m3' => ['nullable', 'numeric', 'min:0'],
            'valeur_estimee' => ['nullable', 'numeric', 'min:0'],
            'lieu_livraison' => ['nullable', 'string', 'max:120'],
            'statut' => ['required', 'in:chargee,en_transit,livree,retour'],
        ]);
    }

    public function index(Request $request)
    {
        $query = MarchandiseTransportee::with(['client', 'trajet', 'marchandise']);

        if ($request->filled('search')) {
            $query->where('lieu_livraison', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $marchandises = $query->paginate(10);
        $clients = Client::all();
        $stats = [
            'chargee' => MarchandiseTransportee::where('statut', 'chargee')->count(),
            'en_transit' => MarchandiseTransportee::where('statut', 'en_transit')->count(),
            'livree' => MarchandiseTransportee::where('statut', 'livree')->count(),
            'retour' => MarchandiseTransportee::where('statut', 'retour')->count(),
        ];

        return view('marchandises_transportees.index', compact('marchandises', 'clients', 'stats'));
    }

    public function create()
    {
        return view('marchandises_transportees.form', [
            'marchandiseTransportee' => null,
            'trajets' => Trajet::with('itineraire')->get(),
            'clients' => Client::all(),
            'marchandises' => Marchandise::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTransport($request, true);

        try {
            foreach ($validated['marchandises'] as $data) {
                MarchandiseTransportee::create([
                    'trajet_id' => $data['trajet_id'],
                    'client_id' => $validated['client_id'],
                    'marchandise_id' => $data['marchandise_id'],
                    'quantite' => $data['quantite'],
                    'poids_kg' => $data['poids_kg'] ?? null,
                    'volume_m3' => $data['volume_m3'] ?? null,
                    'valeur_estimee' => $data['valeur_estimee'] ?? null,
                    'lieu_livraison' => $data['lieu_livraison'] ?? null,
                    'statut' => $data['statut'],
                ]);
            }

            return redirect()->route('marchandises-transportees.index')->with('success', 'Marchandises transportées ajoutées.');
        } catch (Exception $e) {
            Log::error('Erreur lors de l’ajout : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout.']);
        }
    }

    public function edit(MarchandiseTransportee $marchandiseTransportee)
    {
        return view('marchandises_transportees.form', [
            'marchandiseTransportee' => $marchandiseTransportee,
            'trajets' => Trajet::with('itineraire')->get(),
            'clients' => Client::all(),
            'marchandises' => Marchandise::all(),
        ]);
    }

    public function update(Request $request, MarchandiseTransportee $marchandiseTransportee)
    {
        $validated = $this->validateTransport($request);

        try {
            $marchandiseTransportee->update($validated);
            return redirect()->route('marchandises-transportees.index')->with('success', 'Mise à jour réussie.');
        } catch (Exception $e) {
            Log::error('Erreur MAJ : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy(MarchandiseTransportee $marchandiseTransportee)
    {
        try {
            $marchandiseTransportee->delete();
            return redirect()->route('marchandises-transportees.index')->with('success', 'Supprimée avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur suppression : ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}
