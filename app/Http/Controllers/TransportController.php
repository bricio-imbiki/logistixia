<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Chauffeur;
use App\Models\Marchandise;
use App\Models\Trajet;
use App\Models\Client;
use App\Models\Itineraire;
use App\Models\Remorque;
use App\Models\Transport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Exception;

class TransportController extends Controller
{
    protected function validateTransport(Request $request): array
    {
        return $request->validate([
            'client_id' => ['required', 'exists:clients,id'],
            'trajet_id' => ['required', 'exists:trajets,id'],
            'marchandise_id' => ['required', 'exists:marchandises,id'],
            'quantite' => ['required', 'numeric', 'min:0.01'],
            'volume_m3' => ['nullable', 'numeric', 'min:0'],
            'lieu_livraison' => ['nullable', 'string', 'max:120'],
            'statut' => ['required', 'in:chargee,en_transit,livree,retour'],
            'poids_kg' => ['nullable', 'numeric', 'min:0'], // Client-side calculated, validated server-side
            'valeur_estimee' => ['nullable', 'numeric', 'min:0'], // Client-side calculated, validated server-side
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

        $transports = $query->paginate(10);
        $clients = Client::all();
        $stats = [
            'chargee' => Transport::where('statut', 'chargee')->count(),
            'en_transit' => Transport::where('statut', 'en_transit')->count(),
            'livree' => Transport::where('statut', 'livree')->count(),
            'retour' => Transport::where('statut', 'retour')->count(),
        ];

        return view('transports.dernierexempleindex', compact('transports', 'clients', 'stats'));
    }

    public function create()
    {
        return view('transports.form', [
            'transport' => null,
            'trajets' => Trajet::with('itineraire')->get(),
            'clients' => Client::all(),
            'camions' => Camion::all(),
            'chauffeurs' => Chauffeur::all(),
            'remorques' => Remorque::all(),
            'itineraires' => Itineraire::all(),
            'marchandises' => Marchandise::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTransport($request);

        try {
            // Recalculate poids_kg and valeur_estimee server-side
            $marchandise = Marchandise::findOrFail($validated['marchandise_id']);
            $poids_kg = $validated['quantite'] * ($marchandise->poids_moyen ?? 0);
            $valeur_estimee = $validated['quantite'] * ($marchandise->tarif_par_defaut ?? 0);

            // Create transport
            Transport::create([
                'client_id' => $validated['client_id'],
                'trajet_id' => $validated['trajet_id'],
                'marchandise_id' => $validated['marchandise_id'],
                'quantite' => $validated['quantite'],
                'poids_kg' => $poids_kg,
                'volume_m3' => $validated['volume_m3'] ?? null,
                'valeur_estimee' => $valeur_estimee,
                'lieu_livraison' => $validated['lieu_livraison'] ?? null,
                'statut' => $validated['statut'],
            ]);

            return redirect()->route('transports.dernierexempleindex')->with('success', 'Transport ajouté avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de l’ajout du transport : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout du transport.']);
        }
    }

    public function edit(Transport $transport)
    {
        return view('transports.form', [
            'transport' => $transport,
            'trajets' => Trajet::with('itineraire')->get(),
            'clients' => Client::all(),
            'marchandises' => Marchandise::all(),
        ]);
    }

    public function update(Request $request, Transport $transport)
    {
        $validated = $this->validateTransport($request);

        try {
            // Recalculate poids_kg and valeur_estimee server-side
            $marchandise = Marchandise::findOrFail($validated['marchandise_id']);
            $poids_kg = $validated['quantite'] * ($marchandise->poids_moyen ?? 0);
            $valeur_estimee = $validated['quantite'] * ($marchandise->tarif_par_defaut ?? 0);

            // Update transport
            $transport->update([
                'client_id' => $validated['client_id'],
                'trajet_id' => $validated['trajet_id'],
                'marchandise_id' => $validated['marchandise_id'],
                'quantite' => $validated['quantite'],
                'poids_kg' => $poids_kg,
                'volume_m3' => $validated['volume_m3'] ?? null,
                'valeur_estimee' => $valeur_estimee,
                'lieu_livraison' => $validated['lieu_livraison'] ?? null,
                'statut' => $validated['statut'],
            ]);

            return redirect()->route('transports.index')->with('success', 'Transport mis à jour avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la mise à jour du transport : ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour du transport.']);
        }
    }

    public function destroy(Transport $transport)
    {
        try {
            $transport->delete();
            return redirect()->route('transports.index')->with('success', 'Transport supprimé avec succès.');
        } catch (Exception $e) {
            Log::error('Erreur lors de la suppression du transport : ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la suppression du transport.']);
        }
    }
}
