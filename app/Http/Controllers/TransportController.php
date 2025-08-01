<?php

namespace App\Http\Controllers;

use App\Models\Marchandise;
use App\Models\Trajet;
use App\Models\Client;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;



class TransportController extends Controller
{

    protected function validateTransport(Request $request, bool $isMultiple = false): array
    {
        if ($isMultiple) {
            return $request->validate([
                'client_id' => ['required', 'exists:clients,id'],
                'transports' => ['required', 'array', 'min:1'],
                'transports.*.trajet_id' => ['required', 'exists:trajets,id'],
                'transports.*.marchandise_id' => ['required', 'exists:marchandises,id'],
                'transports.*.quantite' => ['required', 'numeric', 'min:0'],
                'transports.*.poids_kg' => ['nullable', 'numeric', 'min:0'],
                'transports.*.volume_m3' => ['nullable', 'numeric', 'min:0'],
                'transports.*.valeur_estimee' => ['nullable', 'numeric', 'min:0'],
                'transports.*.lieu_livraison' => ['nullable', 'string', 'max:120'],
                'transports.*.statut' => ['nullable', 'in:chargee,en_transit,livree,retour'],
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
            'statut' => ['nullable', 'in:chargee,en_transit,livree,retour'],
        ]);
    }

    public function index(Request $request)
    {
        $query = Transport::with(['client', 'trajet', 'marchandise']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('lieu_livraison', 'like', "%$search%")
                  ->orWhereHas('marchandise', fn($q) => $q->where('nom', 'like', "%$search%"))
                  ->orWhereHas('client', fn($q) => $q->where('raison_sociale', 'like', "%$search%"));
            });
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
            'chargee' => Transport::where('statut', 'chargee')->count(),
            'en_transit' => Transport::where('statut', 'en_transit')->count(),
            'livree' => Transport::where('statut', 'livree')->count(),
            'retour' => Transport::where('statut', 'retour')->count(),
        ];

        return view('transports.index', compact('marchandises', 'clients', 'stats'));
    }

    public function create()
    {
        return view('transports.form', [
            'Transport' => null,
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
                Transport::create([
                    'trajet_id' => $data['trajet_id'],
                    'client_id' => $validated['client_id'],
                    'marchandise_id' => $data['marchandise_id'],
                    'quantite' => $data['quantite'],
                    'poids_kg' => $data['poids_kg'] ?? null,
                    'volume_m3' => $data['volume_m3'] ?? null,
                    'valeur_estimee' => $data['valeur_estimee'] ?? null,
                    'lieu_livraison' => $data['lieu_livraison'] ?? null,
                    'statut' => $data['statut'] ?? 'chargee',
                ]);
            }

            return redirect()->route('transports.index')->with('success', 'Marchandises transportées ajoutées.');
        } catch (Exception $e) {
            Log::error('Erreur lors de l’ajout : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout.']);
        }
    }

    public function edit(Transport $Transport)
    {
        return view('transports.form', [
            'Transport' => $Transport,
            'trajets' => Trajet::with('itineraire')->get(),
            'clients' => Client::all(),
            'marchandises' => Marchandise::all(),
        ]);
    }

    public function update(Request $request, Transport $Transport)
    {
        $validated = $this->validateTransport($request);

        try {
            $Transport->update([
                'trajet_id' => $validated['trajet_id'],
                'client_id' => $validated['client_id'],
                'marchandise_id' => $validated['marchandise_id'],
                'quantite' => $validated['quantite'],
                'poids_kg' => $validated['poids_kg'] ?? null,
                'volume_m3' => $validated['volume_m3'] ?? null,
                'valeur_estimee' => $validated['valeur_estimee'] ?? null,
                'lieu_livraison' => $validated['lieu_livraison'] ?? null,
                'statut' => $validated['statut'] ?? 'chargee',
            ]);
            return redirect()->route('transports.index')->with('success', 'Mise à jour réussie.');
        } catch (Exception $e) {
            Log::error('Erreur MAJ : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
        }
    }

    public function destroy(Transport $Transport)
    {
        try {
            $Transport->delete();
            return redirect()->route('transports.index')->with('success', 'Supprimée avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur suppression : ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
        }
    }
}
