<?php

namespace App\Http\Controllers;

use App\Models\Trajet;
use App\Models\Camion;
use App\Models\Remorque;
use App\Models\Chauffeur;
use App\Models\Itineraire;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Exception;
use Illuminate\Database\QueryException;

class TrajetController extends Controller
{
    protected function validateTrajet(Request $request): array
    {
        return $request->validate([
            'camion_id' => ['nullable', 'exists:camions,id'],
            'remorque_id' => ['nullable', 'exists:remorques,id'],
            'chauffeur_id' => ['nullable', 'exists:chauffeurs,id'],
            'itineraire_id' => ['nullable', 'exists:itineraires,id'],
            'date_depart' => ['nullable', 'date'],
            'date_arrivee_etd' => ['nullable', 'date'],
            'date_arrivee_eta' => ['nullable', 'date'],
            'statut' => ['required', 'in:prevu,en_cours,termine,annule'],
            'commentaire' => ['nullable', 'string'],
        ]);
    }

    public function index(Request $request)
    {
        $query = Trajet::with(['camion', 'remorque', 'chauffeur', 'itineraire']);

        if ($search = $request->input('search')) {
            $query->whereHas('camion', function ($q) use ($search) {
                $q->where('matricule', 'like', "%{$search}%");
            });
        }

        $trajets = $query->orderByDesc('date_depart')->paginate(10)->withQueryString();

        return view('trajets.index', compact('trajets'));
    }

    public function create()
    {
        return view('trajets.form', [
            'camions' => Camion::all(),
            'remorques' => Remorque::all(),
            'chauffeurs' => Chauffeur::all(),
            'itineraires' => Itineraire::all(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateTrajet($request);

        try {
            Trajet::create($validated);

            return redirect()->route('trajets.index')->with('success', 'Trajet ajouté avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout du trajet : ' . $e->getMessage()]);
        }
    }

    public function ajaxStore(Request $request)
    {
         $validated = $this->validateTrajet($request);


        $trajet = Trajet::create($validated);

        $trajet->load('itineraire');

        return response()->json([
            'success' => true,
            'trajet' => [
                'id' => $trajet->id,
                'lieu_depart' => $trajet->itineraire->lieu_depart,
                'lieu_arrivee' => $trajet->itineraire->lieu_arrivee,
                'date_depart' => Carbon::parse($trajet->date_depart)->format('d/m/Y'),
            ]
        ]);
    }

    public function edit(Trajet $trajet)
    {
        return view('trajets.form', [
            'trajet' => $trajet,
            'camions' => Camion::all(),
            'remorques' => Remorque::all(),
            'chauffeurs' => Chauffeur::all(),
            'itineraires' => Itineraire::all(),
        ]);
    }

    public function update(Request $request, Trajet $trajet)
    {
        $validated = $this->validateTrajet($request);

        try {
            $trajet->update($validated);

            return redirect()->route('trajets.index')->with('success', 'Trajet mis à jour avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    public function destroy(Trajet $trajet, Request $request)
    {
        try {
            // Fallback: Explicitly delete marchandises if cascade is not set
            if ($trajet->marchandises()->count() > 0) {
                $trajet->marchandises()->delete();
            }
            $trajet->delete();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Trajet et marchandises associées supprimés avec succès.'
                ], 200);
            }

            return redirect()->route('trajets.index')->with('success', 'Trajet et marchandises associées supprimés avec succès.');
        } catch (QueryException $e) {
            $message = 'Impossible de supprimer le trajet car il est lié à des marchandises ou d’autres données. Consultez la liste des marchandises pour plus de détails.';
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            return back()->withErrors(['error' => $message]);
        } catch (Exception $e) {
            $message = 'Erreur lors de la suppression : ' . $e->getMessage();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 500);
            }
            return back()->withErrors(['error' => $message]);
        }
    }
}

