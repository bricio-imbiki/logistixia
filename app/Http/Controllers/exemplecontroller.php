<?php

// namespace App\Http\Controllers;
// use App\Models\Slider;
// use App\Models\Vehicule;
// use App\Models\HomeAbout;
// use App\Models\TypeVehicule;
// use Illuminate\Http\Request;
// use Illuminate\Support\Carbon;
// use Illuminate\Support\Facades\DB;
// use Intervention\Image\Facades\Image;
// use Illuminate\Support\Facades\Validator;

// class DashboardController extends Controller
// {

//     public function __construct()
//     {
//         $this->middleware('auth');
//     }
//     public function Dashboard()
//     {
//         $clients = DB::table('users')->where('usertype', 'client')->get();
//         $users = DB::table('users')->get();
//         return view('dashboard', compact('users','clients'));
//     }
//     //Home page controller dashboard
//     public function HomeAbout()
//     {
//         $homeabout = HomeAbout::latest()->get();
//         return view('dashboard.home.index', compact('homeabout'));
//     }
//     public function HomeAdd()
//     {
//         return view('dashboard.home.create');
//     }

//     public function StoreAbout(Request $request)
//     {
//         HomeAbout::insert([
//             'title' => $request->title,
//             'short_desc' => $request->short_desc,
//             'long_desc' =>  $request->long_desc,
//             'created_at' => Carbon::now(),

//         ]);
//         return redirect()->route('home.about')->with('success', 'About insert successfully ');
//     }
//     public function EditAbout($id)
//     {
//         $homeabout = HomeAbout::find($id);
//         return view('home.edit', compact('homeabout'));
//     }
//     public function UpdateAbout(Request $request, $id)
//     {
//         HomeAbout::find($id)->update([
//             'title' => $request->title,
//             'short_desc' => $request->short_desc,
//             'long_desc' =>  $request->long_desc,
//         ]);
//         return redirect()->route('home.about')->with('success', 'About update successfully ');
//     }

//     public function DeleteAbout($id)
//     {
//         HomeAbout::find($id)->Delete();
//         return Redirect()->back()->with('success', 'About Delete Successfully');
//     }
// //slider controller dashboard
//     public function HomeSlider()
//     {

//         $sliders = Slider::latest()->get();
//         return view('dashboard.slider.index', compact('sliders'));
//     }

//     public function AddSlider()
//     {
//         return view('dashboard.slider.create');
//     }
//     public function StoreSlider(Request $request)
//     {
//         $slider_image = $request->file('image');

//         if ($slider_image) {
//             $name_gen = hexdec(uniqid()) . '.' . $slider_image->getClientOriginalExtension();
//             Image::make($slider_image)->resize(1920, 1088)->save('public/image/slider/' . $name_gen);
//             $last_img = 'public/image/slider/' . $name_gen;
//         } else {

//             return redirect()->back()->with('error', 'Aucun fichier n\'a été téléchargé.');
//         }

//         Slider::insert([

//             'title' => $request->title,
//             'description' =>  $request->description,
//             'image' =>  $last_img,
//             'created_at' => Carbon::now(),
//         ]);

//         return Redirect()->route('home.slider')->with('success', 'slider insert succesfully');
//     }
//     public function DeleteSlider($id)
//     {
//         $image = Slider::find($id);
//         $old_image = $image->image;
//         unlink($old_image);

//         Slider::find($id)->forceDelete();
//         return Redirect()->back()->with('success', 'Slider Delete Successfully');
//     }
//     public function EditSlider($id)
//     {
//         $image = Slider::find($id);
//         return view('dashboard.slider.edit', compact('image'));
//     }

//     public function UpdateSlider(Request $request, $id)
//     {
//         $validateData = $request->validate(
//             [
//                 'title' => 'required|unique:sliders|min:4',
//                 'description' => 'required|unique:sliders|min:4',
//                 'image' => 'required|mimes:jpg,jpeg,png',
//             ],

//             [
//                 'title.required' => 'Please Input slider title',
//                 'description.required' => 'Please Input slider description',
//                 'image.min' => 'slider Longer then 4 Characters',
//             ]
//         );


//         $slider_image = $request->file('image');
//         $old_image = $request->image;


//         if ($slider_image) {
//             $name_gen = hexdec(uniqid()) . '.' . $slider_image->getClientOriginalExtension();
//             Image::make($slider_image)->resize(1920, 1088)->save('public/image/slider/' . $name_gen);
//             $last_img = 'public/image/slider/' . $name_gen;

//             unlink($old_image);
//             Slider::find($id)->update([
//                 'title' => $request->title,
//                 'description' => $request->description,
//                 'image' => $last_img,
//                 'created_at' => Carbon::now()
//             ]);
//         }
//         return Redirect()->route('home.slider')->with('success', 'Slider  Updated Successfully');
//     }



// //vehicule controller dashboard
//     public function AllVehicule()
//     {
//         $typesVehicules = TypeVehicule::all();
//         $vehicules = Vehicule::with('typeVehicule')->paginate(5);
//         return view('dashboard.vehicules.index', compact('vehicules',));
//     }
//     public function AddVehicule()
//     {
//         $types = TypeVehicule::distinct()->pluck('type');


//         return view('dashboard.vehicules.create', compact('types'));
//     }
//     public function getNomsByType($type)
//     {
//         $noms = TypeVehicule::where('type', $type)->pluck('nom', 'id');

//         return response()->json($noms);
//     }
//     public function StoreVehicule(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'type_vehicule_id' => 'required|exists:type_vehicules,id',
//             'nom_id' => 'required|exists:type_vehicules,id',
//             'model' => 'required|unique:vehicules|min:4',
//             'marque' => 'required|min:4',
//             'prix_journalier' => 'required',
//             'availability' => 'required',
//             'image' => 'required|mimes:jpg,jpeg,png',
//         ]);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         $vehicule_image = $request->file('image');
//         $name_gen = hexdec(uniqid()) . '.' . $vehicule_image->getClientOriginalExtension();
//         Image::make($vehicule_image)->resize(300, 200)->save('public/image/vehicules/' . $name_gen);
//         $last_img = 'public/image/vehicules/' . $name_gen;
//         Vehicule::insert([
//             'type_vehicule_id' => $request->type_vehicule_id,
//             'nom_id' => $request->type_vehicule_id,
//             'marque' => $request->marque,
//             'model' => $request->model,
//             'prix_journalier' => $request->prix_journalier,
//             'availability' => $request->availability,
//             'image' => $last_img,
//             'created_at' => Carbon::now(),
//         ]);


//         return redirect()->route('all.vehicule')->with('success', 'Vehicule insert succesfully');
//     }


//     public function EditVehicule($id)
//     {
//         $vehicules = Vehicule::findOrFail($id);
//         $typesVehicules = TypeVehicule::all();
//         return view('dashboard.vehicules.edit', compact('vehicules', 'typesVehicules'));
//     }

//     public function UpdateVehicule(Request $request, $id)
//     {
//         $validator = Validator::make($request->all(), [
//             'type_vehicule_id' => 'required|exists:type_vehicules,id',
//             'nom_id' => 'required|exists:type_vehicules,id',
//             'marque' => 'required|min:4',
//             'model' => 'required|min:4',
//             'prix_journalier' => 'required',
//             'availability' => 'required',
//             'image' => 'image|mimes:jpg,jpeg,png',
//         ]);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         $vehicule = Vehicule::findOrFail($id);

//         // Supprimer l'ancienne image si une nouvelle est fournie
//         if ($request->hasFile('image')) {
//             $vehicule_image = $request->file('image');
//             $name_gen = hexdec(uniqid());
//             $img_ext = strtolower($vehicule_image->getClientOriginalExtension());
//             $img_name = $name_gen . '.' . $img_ext;
//             $up_location = 'public/image/vehicules/';
//             $last_img = $up_location . $img_name;

//             // Déplacer la nouvelle image
//             $vehicule_image->move($up_location, $img_name);

//             // Supprimer l'ancienne image
//             if (file_exists($vehicule->image)) {
//                 unlink($vehicule->image);
//             }

//             $vehicule->image = $last_img;
//         }

//         // Mettre à jour les autres champs du véhicule
//         $vehicule->type_vehicule_id = $request->type_vehicule_id;
//         $vehicule->nom_id = $request->nom_id;
//         $vehicule->marque = $request->marque;
//         $vehicule->model = $request->model;
//         $vehicule->prix_journalier = $request->prix_journalier;
//         $vehicule->availability = $request->availability;

//         $vehicule->save();

//         return redirect()->route('all.vehicule')->with('success', 'Véhicule mis à jour avec succès');
//     }


//     public function DeleteVehicule($id)
//     {
//         $image = Vehicule::find($id);
//         $old_image = $image->image;
//         unlink($old_image);

//         Vehicule::find($id)->forceDelete();
//         return Redirect()->back()->with('success', 'vehicule Delete Successfully');
//     }
//     //TypeVehicule controller dashboard
//     public function TypeVehiculeAll()
//     {
//         $typesVehicules = TypeVehicule::all();
//         return view('dashboard.types_vehicules.index', compact('typesVehicules'));
//     }

//     public function TypeVehiculeAdd()
//     {
//         return view('dashboard.types_vehicules.create');
//     }

//     public function TypeVehiculeStore(Request $request)
//     {
//         $request->validate([
//             'type' => 'required|min:4',
//             'name' => 'required|min:4',
//         ]);

//         TypeVehicule::insert([
//             'type' => $request->type,
//             'name' => $request->name,
//         ]);

//         return redirect()->route('typeVehicule.index')->with('success', 'Type de véhicule ajouté avec succès');
//     }

//     public function TypeVehiculeEdit($id)
//     {
//         $typeVehicules = TypeVehicule::findOrFail($id);
//         return view('dashboard.types_vehicules.edit', compact('typeVehicules'));
//     }

//     public function TypeVehiculeUpdate(Request $request, $id)
//     {

//         $validator = Validator::make($request->all(), [
//             'type' => 'required|min:4',
//             'name' => 'required|min:4',
//         ]);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         $typeVehicules = TypeVehicule::findOrFail($id);
//         $typeVehicules->update([
//             'type' => $request->type,
//             'name' => $request->name,
//         ]);

//         return Redirect()->route('all.vehicule')->with('success', 'vehicule Updated Successfully');
//     }

//     public function TypeVehiculeDelete($id)
//     {
//         $typeVehicule = TypeVehicule::findOrFail($id);

//         // Vérifier s'il existe des véhicules associés à ce type
//         $associatedVehicules = $typeVehicule->vehicules;

//         if ($associatedVehicules->isNotEmpty()) {
//             // Supprimer les véhicules associés
//             foreach ($associatedVehicules as $vehicule) {
//                 $vehicule->delete();
//             }
//         }

//         $typeVehicule->delete();

//         return redirect()->back()->with('success', 'Type de véhicule supprimé avec succès');
//     }
// }




// namespace App\Http\Controllers;

// use App\Models\Camion;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Validator;
// use Intervention\Image\Facades\Image;

// class CamionController extends Controller
// {
//     public function index()
//     {
//         $camions = Camion::latest()->paginate(10);
//         return view('camions.index', compact('camions'));
//     }

//     public function create()
//     {
//         return view('camions.create');
//     }

//     public function store(Request $request)
//     {
//         $validator = Validator::make($request->all(), [
//             'matricule' => 'required|string|max:20|unique:camions,matricule',
//             'marque' => 'nullable|string|max:50',
//             'modele' => 'nullable|string|max:50',
//             'annee' => 'nullable|digits:4|integer',
//             'capacite_kg' => 'nullable|integer',
//             'statut' => 'required|in:disponible,en_mission,panne,maintenance',
//             'est_interne' => 'nullable|boolean',
//             'societe_proprietaire' => 'nullable|string|max:120',
//             'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//         ]);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         $data = $validator->validated();

//         // Gestion de l'image si présente
//         if ($request->hasFile('photo_url')) {
//             $image = $request->file('photo_url');
//             $filename = uniqid('camion_') . '.' . $image->getClientOriginalExtension();

//             // Sauvegarder l'original
//             $originalPath = "uploads/camions/originals/{$filename}";
//             Storage::disk('public')->put($originalPath, file_get_contents($image));

//             // Sauvegarder la miniature
//             $thumbnailPath = "uploads/camions/thumbnails/{$filename}";
//             $thumbnail = Image::make($image)->resize(300, 200)->encode();
//             Storage::disk('public')->put($thumbnailPath, $thumbnail);

//             $data['photo_url'] = $originalPath;
//         }

//         Camion::create($data);

//         return redirect()->route('camions.index')->with('success', 'Camion ajouté avec succès.');
//     }

//     public function show(Camion $camion)
//     {
//         return view('camions.show', compact('camion'));
//     }

//     public function edit(Camion $camion)
//     {
//         return view('camions.edit', compact('camion'));
//     }

//     public function update(Request $request, Camion $camion)
//     {
//         $validator = Validator::make($request->all(), [
//             'matricule' => 'required|string|max:20|unique:camions,matricule,' . $camion->id,
//             'marque' => 'nullable|string|max:50',
//             'modele' => 'nullable|string|max:50',
//             'annee' => 'nullable|digits:4|integer',
//             'capacite_kg' => 'nullable|integer',
//             'statut' => 'required|in:disponible,en_mission,panne,maintenance',
//             'est_interne' => 'nullable|boolean',
//             'societe_proprietaire' => 'nullable|string|max:120',
//             'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//         ]);

//         if ($validator->fails()) {
//             return redirect()->back()->withErrors($validator)->withInput();
//         }

//         $data = $validator->validated();

//         // Si nouvelle image
//         if ($request->hasFile('photo_url')) {
//             // Supprimer anciennes images
//             if ($camion->photo_url && Storage::disk('public')->exists($camion->photo_url)) {
//                 Storage::disk('public')->delete($camion->photo_url);
//                 $thumbPath = str_replace('/originals/', '/thumbnails/', $camion->photo_url);
//                 Storage::disk('public')->delete($thumbPath);
//             }

//             $image = $request->file('photo_url');
//             $filename = uniqid('camion_') . '.' . $image->getClientOriginalExtension();

//             // Sauvegarde
//             $originalPath = "uploads/camions/originals/{$filename}";
//             Storage::disk('public')->put($originalPath, file_get_contents($image));

//             $thumbnailPath = "uploads/camions/thumbnails/{$filename}";
//             $thumbnail = Image::make($image)->resize(300, 200)->encode();
//             Storage::disk('public')->put($thumbnailPath, $thumbnail);

//             $data['photo_url'] = $originalPath;
//         }

//         $camion->update($data);

//         return redirect()->route('camions.index')->with('success', 'Camion mis à jour avec succès.');
//     }

//     public function destroy(Camion $camion)
//     {
//         if ($camion->photo_url && Storage::disk('public')->exists($camion->photo_url)) {
//             Storage::disk('public')->delete($camion->photo_url);
//             $thumbPath = str_replace('/originals/', '/thumbnails/', $camion->photo_url);
//             Storage::disk('public')->delete($thumbPath);
//         }

//         $camion->delete();

//         return redirect()->route('camions.index')->with('success', 'Camion supprimé avec succès.');
//     }
// }



// namespace App\Http\Livewire;

// use Livewire\Component;
// use App\Models\Client;
// use Livewire\WithPagination;

// class ClientCrud extends Component
// {
//     use WithPagination;

//     public $raison_sociale, $contact, $telephone, $email, $adresse, $type_client;
//     public $client_id;
//     public $updateMode = false;

//     protected $rules = [
//         'raison_sociale' => 'required|string|max:120',
//         'contact'       => 'nullable|string|max:100',
//         'telephone'     => 'nullable|string|max:20',
//         'email'         => 'nullable|email|max:120',
//         'adresse'       => 'nullable|string',
//         'type_client'   => 'required|in:industriel,commercial,particulier',
//     ];

//     public function render()
//     {
//         $clients = Client::orderBy('created_at', 'desc')->paginate(10);
//         return view('livewire.client-crud', ['clients' => $clients]);
//     }

//     public function resetInputFields()
//     {
//         $this->raison_sociale = '';
//         $this->contact = '';
//         $this->telephone = '';
//         $this->email = '';
//         $this->adresse = '';
//         $this->type_client = 'industriel';
//         $this->client_id = null;
//     }

//     public function store()
//     {
//         $this->validate();

//         Client::create([
//             'raison_sociale' => $this->raison_sociale,
//             'contact'       => $this->contact,
//             'telephone'     => $this->telephone,
//             'email'         => $this->email,
//             'adresse'       => $this->adresse,
//             'type_client'   => $this->type_client,
//         ]);

//         session()->flash('message', 'Client créé avec succès.');

//         $this->resetInputFields();
//     }

//     public function edit($id)
//     {
//         $client = Client::findOrFail($id);
//         $this->client_id = $id;
//         $this->raison_sociale = $client->raison_sociale;
//         $this->contact = $client->contact;
//         $this->telephone = $client->telephone;
//         $this->email = $client->email;
//         $this->adresse = $client->adresse;
//         $this->type_client = $client->type_client;

//         $this->updateMode = true;
//     }

//     public function cancel()
//     {
//         $this->resetInputFields();
//         $this->updateMode = false;
//     }

//     public function update()
//     {
//         $this->validate();

//         if ($this->client_id) {
//             $client = Client::find($this->client_id);
//             $client->update([
//                 'raison_sociale' => $this->raison_sociale,
//                 'contact'       => $this->contact,
//                 'telephone'     => $this->telephone,
//                 'email'         => $this->email,
//                 'adresse'       => $this->adresse,
//                 'type_client'   => $this->type_client,
//             ]);

//             session()->flash('message', 'Client mis à jour avec succès.');

//             $this->resetInputFields();
//             $this->updateMode = false;
//         }
//     }

//     public function delete($id)
//     {
//         if ($id) {
//             Client::where('id', $id)->delete();
//             session()->flash('message', 'Client supprimé avec succès.');
//         }
//     }
// }


// use Intervention\Image\Facades\Image;

// public function store(Request $request)
// {
//     $validator = Validator::make($request->all(), [
//         'matricule' => 'required|string|max:20|unique:camions,matricule',
//         'marque' => 'nullable|string|max:50',
//         'modele' => 'nullable|string|max:50',
//         'annee' => 'nullable|digits:4|integer',
//         'capacite_kg' => 'nullable|integer',
//         'statut' => 'required|in:disponible,en_mission,panne,maintenance',
//         'est_interne' => 'nullable|boolean',
//         'societe_proprietaire' => 'nullable|string|max:120',
//         'photo_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//     ]);

//     if ($validator->fails()) {
//         return redirect()->back()->withErrors($validator)->withInput();
//     }

//     $data = $validator->validated();

//     // Si une image est uploadée
//     if ($request->hasFile('photo_url')) {
//         $image = $request->file('photo_url');
//         $filename = 'camion_' . uniqid() . '.' . $image->getClientOriginalExtension();
//         $path = 'images/camions/' . $filename;

//         // Créer le dossier s'il n'existe pas
//         if (!File::exists(public_path('images/camions/'))) {
//             File::makeDirectory(public_path('images/camions/'), 0755, true);
//         }

//         // Resize et sauvegarder
//         Image::load($image)->resize(600, 400)->save(public_path($path));

//         $data['photo_url'] = $path;
//     }

//     Camion::create($data);

//     return redirect()->route('camions.index')->with('success', 'Camion ajouté avec succès.');
// }





