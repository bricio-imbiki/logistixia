<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;
use Intervention\Image\Laravel\Facades\Image;

class ChauffeurController extends Controller
{
    // Validation réutilisable
    protected function validateChauffeur(Request $request, $chauffeurId = null): array
    {
        return $request->validate([
            'nom' => ['required', 'string', 'max:60'],
            'prenom' => ['nullable', 'string', 'max:60'],
            'date_naissance' => ['nullable', 'date'],
            'telephone' => ['nullable', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:120'],
            'adresse' => ['nullable', 'string'],
            'permis_num' => ['nullable', 'string', 'max:50'],
            'permis_categorie' => ['nullable', 'string', 'max:10'],
            'permis_expire' => ['nullable', 'date'],
            'statut' => ['required', 'in:titulaire,remplacant'],
            'date_embauche' => ['nullable', 'date'],
            'experience_annees' => ['nullable', 'integer', 'min:0'],
            'cin_num' => ['nullable', 'string', 'max:30'],
            'apte_medicalement' => ['required', 'boolean'],
            'photo_url' => ['nullable', 'image', 'max:2048'], // 2MB
            'document_permis' => ['nullable', 'file', 'max:2048'],
        ]);
    }

    public function index(Request $request)
    {
        $query = Chauffeur::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('permis_num', 'like', "%{$search}%");
            });
        }

        $chauffeurs = $query->orderBy('nom')->paginate(10)->withQueryString();

        return view('chauffeurs.index', compact('chauffeurs'));
    }

    public function create()
    {
        return view('chauffeurs.form');
    }

    public function store(Request $request)
    {
        $validated = $this->validateChauffeur($request);

        $photoPath = null;
        $docPath = null;
        $photoFolder = 'image/chauffeurs';
        $docFolder = 'documents/permis';

        try {
            // Photo
            if ($request->hasFile('photo_url')) {
                $photo = $request->file('photo_url');
                $fileName = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $image = Image::read($photo)->resize(300, 300)->encode();
                Storage::disk('public')->put("$photoFolder/$fileName", $image);
                $photoPath = "$photoFolder/$fileName";
            }

            // Permis document
            if ($request->hasFile('document_permis')) {
                $file = $request->file('document_permis');
                $docName = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $docPath = $file->storeAs($docFolder, $docName, 'public');
            }

            Chauffeur::create(array_merge($validated, [
                'photo_url' => $photoPath,
                'document_permis' => $docPath,
            ]));

            return redirect()->route('chauffeurs.index')->with('success', 'Chauffeur ajouté avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout : ' . $e->getMessage()]);
        }
    }

    public function edit(Chauffeur $chauffeur)
    {
        return view('chauffeurs.form', compact('chauffeur'));
    }

    public function update(Request $request, Chauffeur $chauffeur)
    {
        $validated = $this->validateChauffeur($request, $chauffeur->id);

        $photoFolder = 'image/chauffeurs';
        $docFolder = 'documents/permis';

        try {
            // Photo
            if ($request->hasFile('photo_url')) {
                $photo = $request->file('photo_url');
                $fileName = Str::random(20) . '.' . $photo->getClientOriginalExtension();
                $image = Image::read($photo)->resize(300, 300)->encode();
                Storage::disk('public')->put("$photoFolder/$fileName", $image);

                if ($chauffeur->photo_url && Storage::disk('public')->exists($chauffeur->photo_url)) {
                    Storage::disk('public')->delete($chauffeur->photo_url);
                }

                $validated['photo_url'] = "$photoFolder/$fileName";
            } else {
                $validated['photo_url'] = $chauffeur->photo_url;
            }

            // Document permis
            if ($request->hasFile('document_permis')) {
                $file = $request->file('document_permis');
                $docName = Str::random(20) . '.' . $file->getClientOriginalExtension();
                $docPath = $file->storeAs($docFolder, $docName, 'public');

                if ($chauffeur->document_permis && Storage::disk('public')->exists($chauffeur->document_permis)) {
                    Storage::disk('public')->delete($chauffeur->document_permis);
                }

                $validated['document_permis'] = $docPath;
            } else {
                $validated['document_permis'] = $chauffeur->document_permis;
            }

            $chauffeur->update($validated);

            return redirect()->route('chauffeurs.index')->with('success', 'Chauffeur mis à jour avec succès.');
        } catch (Exception $e) {
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
        }
    }

    public function destroy(Chauffeur $chauffeur)
    {
        try {
            if ($chauffeur->photo_url && Storage::disk('public')->exists($chauffeur->photo_url)) {
                Storage::disk('public')->delete($chauffeur->photo_url);
            }

            if ($chauffeur->document_permis && Storage::disk('public')->exists($chauffeur->document_permis)) {
                Storage::disk('public')->delete($chauffeur->document_permis);
            }

            $chauffeur->delete();

            return redirect()->route('chauffeurs.index')->with('success', 'Chauffeur supprimé avec succès.');
        } catch (Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
        }
    }
}
