<?php

namespace App\Http\Controllers;

use App\Models\Remorque;
use App\Models\Camion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Intervention\Image\Laravel\Facades\Image;
use Illuminate\Support\Str;
use Exception;


class RemorqueController extends Controller
{
    // Validation réutilisable
    protected function validateRemorque(Request $request, $remorqueId = null): array
    {
        return $request->validate([
            'matricule' => ['required', 'string', 'max:20', 'unique:remorques,matricule,' . $remorqueId],
            'type' => ['nullable', 'string', 'max:60'],
            'capacite_max' => ['nullable', 'numeric', 'min:0'],
            'est_interne' => ['required', 'boolean'],
            'societe_proprietaire' => ['nullable', 'string', 'max:120'],
            'photo_url' => ['nullable', 'image', 'max:2048'], // 2MB max
            'camion_id' => ['nullable', 'exists:camions,id'],
        ]);
    }

    // Liste des remorques avec recherche possible
    public function index(Request $request)
    {
        $query = Remorque::with('camion');

        if ($search = $request->input('search')) {
            $query->where('matricule', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%")
                  ->orWhereHas('camion', function ($q) use ($search) {
                      $q->where('matricule', 'like', "%{$search}%");
                  });
        }

        $remorques = $query->orderBy('matricule')->paginate(10)->withQueryString();

        return view('remorques.index', compact('remorques'));
    }

    // Formulaire création
   public function create()
{
    $camions = Camion::orderBy('matricule')->get();
    return view('remorques.form', [
        'remorque' => null,
        'camions' => $camions,
    ]);
}


    // Enregistrement
    public function store(Request $request)
    {
        $validated = $this->validateRemorque($request);

        $photoPath = null;
        $folder = 'image/remorques';

        try {
            if ($request->hasFile('photo_url')) {
                $photo = $request->file('photo_url');
                $extension = $photo->getClientOriginalExtension();
                $fileName = Str::random(20) . '.' . $extension;

                $image = Image::read($photo)->resize(300, 200)->encode();

                Storage::disk('public')->put("$folder/$fileName", $image);

                $photoPath = "$folder/$fileName";
            }

            Remorque::create([
                'matricule' => $validated['matricule'],
                'type' => $validated['type'] ?? null,
                'capacite_max' => $validated['capacite_max'] ?? null,
                'est_interne' => $validated['est_interne'],
                'societe_proprietaire' => $validated['societe_proprietaire'] ?? null,
                'photo_url' => $photoPath,
                'camion_id' => $validated['camion_id'] ?? null,
            ]);

            return redirect()->route('remorques.index')->with('success', 'Remorque ajoutée avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout de la remorque : ' . $e->getMessage()]);
        }
    }

    // Formulaire édition
    public function edit(Remorque $remorque)
    {
        $camions = Camion::orderBy('matricule')->get();
        return view('remorques.form', compact('remorque', 'camions'));
    }

    // Mise à jour
    public function update(Request $request, Remorque $remorque)
    {
        $validated = $this->validateRemorque($request, $remorque->id);

        $folder = 'image/remorques';

        try {
            if ($request->hasFile('photo_url')) {
                $photo = $request->file('photo_url');
                $extension = $photo->getClientOriginalExtension();
                $fileName = Str::random(20) . '.' . $extension;

               $image = Image::read($photo)->resize(300, 300)->encode();

                Storage::disk('public')->put("$folder/$fileName", $image);

                // Supprimer ancienne photo
                if ($remorque->photo_url && Storage::disk('public')->exists($remorque->photo_url)) {
                    Storage::disk('public')->delete($remorque->photo_url);
                }

                $validated['photo_url'] = "$folder/$fileName";
            } else {
                $validated['photo_url'] = $remorque->photo_url;
            }

            $remorque->update($validated);

            return redirect()->route('remorques.index')->with('success', 'Remorque mise à jour avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    // Suppression
    public function destroy(Remorque $remorque)
    {
        try {
            if ($remorque->photo_url && Storage::disk('public')->exists($remorque->photo_url)) {
                Storage::disk('public')->delete($remorque->photo_url);
            }

            $remorque->delete();

            return redirect()->route('remorques.index')->with('success', 'Remorque supprimée avec succès.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
}
