<?php

// namespace App\Http\Controllers;

// use App\Models\Camion;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;

// class camionControllerexemple extends Controller
// {
//     // Afficher la liste paginée
//     public function index(Request $request)
//     {
//         $query = Camion::query();

//         // Filtrer par recherche (matricule, marque, modele)
//         if ($search = $request->input('search')) {
//             $query->where(function ($q) use ($search) {
//                 $q->where('matricule', 'like', "%{$search}%")
//                   ->orWhere('marque', 'like', "%{$search}%")
//                   ->orWhere('modele', 'like', "%{$search}%");
//             });
//         }



//     // if ($search = $request->input('search')) {
//     //     $query->where(function ($q) use ($search) {
//     //         $q->where('matricule', 'like', "%{$search}%")
//     //           ->orWhere('marque', 'like', "%{$search}%")
//     //           ->orWhere('modele', 'like', "%{$search}%");
//     //     });
//     // }

//     // $camions = $query->orderBy('id', 'desc')->paginate(10)->withQueryString();


//         $camions = $query->orderBy('matricule')->paginate(10)->withQueryString();

//         return view('camions.index', compact('camions'));
//     }

//     // Afficher formulaire création
//     public function create()
//     {
//         return view('camions.form');
//     }

//     // Stocker nouveau camion
//     public function store(Request $request)
//     {
//         $validated = $this->validateCamion($request);

//         // Gérer l'image
//         if ($request->hasFile('photo_url')) {
//             $validated['photo_url'] = $request->file('photo_url')->store('photos_camions', 'public');
//         }

//         Camion::create($validated);

//         return redirect()->route('camions.index')->with('message', 'Camion créé avec succès !');
//     }

//     // Afficher formulaire édition
//     public function edit(Camion $camion)
//     {
//         return view('camions.form', compact('camion'));
//     }

//     // Mettre à jour camion
//     public function update(Request $request, Camion $camion)
//     {
//         $validated = $this->validateCamion($request, $camion->id);

//         // Gérer l'image
//         if ($request->hasFile('photo_url')) {
//             // Supprimer l’ancienne photo si existante
//             if ($camion->photo_url) {
//                 Storage::disk('public')->delete($camion->photo_url);
//             }

//             $validated['photo_url'] = $request->file('photo_url')->store('photos_camions', 'public');
//         }

//         $camion->update($validated);

//         return redirect()->route('camions.index')->with('message', 'Camion mis à jour avec succès !');
//     }

//     // Supprimer camion
//     public function destroy(Camion $camion)
//     {
//         // Supprimer la photo si existante
//         if ($camion->photo_url) {
//             Storage::disk('public')->delete($camion->photo_url);
//         }

//         $camion->delete();

//         return redirect()->route('camions.index')->with('message', 'Camion supprimé avec succès !');
//     }

//     // Validation commune
//     protected function validateCamion(Request $request, $camionId = null): array
//     {
//         return $request->validate([
//             'matricule' => ['required', 'string', 'max:255', 'unique:camions,matricule,' . $camionId],
//             'marque' => ['nullable', 'string', 'max:255'],
//             'modele' => ['nullable', 'string', 'max:255'],
//             'annee' => ['nullable', 'integer', 'min:1900', 'max:' . date('Y')],
//             'capacite_kg' => ['nullable', 'integer', 'min:0'],
//             'statut' => ['required', 'in:disponible,en_mission,panne,maintenance'],
//             'est_interne' => ['required', 'boolean'],
//             'societe_proprietaire' => ['nullable', 'string', 'max:255'],
//             'photo_url' => ['nullable', 'image', 'max:2048'], // 2MB max
//         ]);
//     }
// }





