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
