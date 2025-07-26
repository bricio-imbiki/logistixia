<?php

namespace App\Http\Controllers;


use App\Models\Camion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Exception;

class CamionController extends Controller
{
    // Validation réutilisable
    protected function validateCamion(Request $request, $camionId = null): array
    {
        return $request->validate([
            'matricule' => ['required', 'string', 'max:255', 'unique:camions,matricule,' . $camionId],
            'marque' => ['nullable', 'string', 'max:255'],
            'modele' => ['nullable', 'string', 'max:255'],
            'annee' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
            'capacite_kg' => ['nullable', 'integer', 'min:0'],
            'statut' => ['required', 'in:disponible,en_mission,panne,maintenance'],
            'est_interne' => ['required', 'boolean'],
            'societe_proprietaire' => ['nullable', 'string', 'max:255'],
            'photo_url' => ['nullable', 'image', 'max:2048'], // 2MB max
        ]);
    }

    public function index(Request $request)
    {
        $query = Camion::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('matricule', 'like', "%{$search}%")
                  ->orWhere('marque', 'like', "%{$search}%")
                  ->orWhere('modele', 'like', "%{$search}%");
            });
        }

        $camions = $query->orderBy('matricule')->paginate(10)->withQueryString();

        return view('camions.index', compact('camions'));
    }

    public function create()
   {
     return view('camions.form');
    }

    public function store(Request $request)
    {
        $validated = $this->validateCamion($request);

        $photoPath = null;
        $folder = 'image/camions';

        try {
            if ($request->hasFile('photo_url')) {
                $photo = $request->file('photo_url');
                $extension = $photo->getClientOriginalExtension();
                $fileName = Str::random(20) . '.' . $extension;

                $image = Image::read($photo)->resize(300, 200)->encode();

                Storage::disk('public')->put("$folder/$fileName", $image);

                $photoPath = "$folder/$fileName";
            }

            Camion::create([
                'matricule' => $validated['matricule'],
                'marque' => $validated['marque'] ?? null,
                'modele' => $validated['modele'] ?? null,
                'annee' => $validated['annee'] ?? null,
                'capacite_kg' => $validated['capacite_kg'] ?? null,
                'statut' => $validated['statut'],
                'est_interne' => $validated['est_interne'],
                'societe_proprietaire' => $validated['societe_proprietaire'] ?? null,
                'photo_url' => $photoPath,
            ]);

            return redirect()->route('camions.index')->with('success', 'Camion ajouté avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout du camion : ' . $e->getMessage()]);
        }
    }

    public function edit(Camion $camion)
    {
        return view('camions.form', compact('camion'));
    }

    public function update(Request $request, Camion $camion)
    {
        $validated = $this->validateCamion($request, $camion->id);

        $folder = 'image/camions';

        try {
            if ($request->hasFile('photo_url')) {
                $photo = $request->file('photo_url');
                $extension = $photo->getClientOriginalExtension();
                $fileName = Str::random(20) . '.' . $extension;

                $image = Image::read($photo)->resize(300, 300)->encode();

                Storage::disk('public')->put("$folder/$fileName", $image);

                if ($camion->photo_url && Storage::disk('public')->exists($camion->photo_url)) {
                    Storage::disk('public')->delete($camion->photo_url);
                }

                $validated['photo_url'] = "$folder/$fileName";
            } else {
                $validated['photo_url'] = $camion->photo_url;
            }

            $camion->update($validated);

            return redirect()->route('camions.index')->with('success', 'Camion mis à jour avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    public function destroy(Camion $camion)
    {
        try {
            if ($camion->photo_url && Storage::disk('public')->exists($camion->photo_url)) {
                Storage::disk('public')->delete($camion->photo_url);
            }

            $camion->delete();

            return redirect()->route('camions.index')->with('success', 'Camion supprimé avec succès.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
}
