<?php

namespace App\Http\Controllers;

use App\Models\Itineraire;
use Illuminate\Http\Request;
use Exception;

class ItineraireController extends Controller
{
    protected function validateItineraire(Request $request, $id = null): array
    {
        return $request->validate([
            'lieu_depart' => ['nullable', 'string', 'max:120'],
            'lieu_arrivee' => ['nullable', 'string', 'max:120'],
            'distance_km' => ['nullable', 'numeric', 'min:0'],
            'duree_estimee_h' => ['nullable', 'numeric', 'min:0'],
            'peage_estime' => ['nullable', 'numeric', 'min:0'],
        ]);
    }

    public function index(Request $request)
    {
        $query = Itineraire::query();

        if ($search = $request->input('search')) {
            $query->where('lieu_depart', 'like', "%{$search}%")
                  ->orWhere('lieu_arrivee', 'like', "%{$search}%");
        }

        $itineraires = $query->orderBy('lieu_depart')->paginate(10)->withQueryString();

        return view('itineraires.index', compact('itineraires'));
    }

    public function create()
    {
        return view('itineraires.form');
    }

    public function store(Request $request)
    {
        $validated = $this->validateItineraire($request);

        try {
            Itineraire::create($validated);
            return redirect()->route('itineraires.index')->with('success', 'Itinéraire ajouté avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout : ' . $e->getMessage()]);
        }
    }

    public function edit(Itineraire $itineraire)
    {
        return view('itineraires.form', compact('itineraire'));
    }

    public function update(Request $request, Itineraire $itineraire)
    {
        $validated = $this->validateItineraire($request, $itineraire->id);

        try {
            $itineraire->update($validated);
            return redirect()->route('itineraires.index')->with('success', 'Itinéraire mis à jour avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    public function destroy(Itineraire $itineraire)
    {
        try {
            $itineraire->delete();
            return redirect()->route('itineraires.index')->with('success', 'Itinéraire supprimé avec succès.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
}
