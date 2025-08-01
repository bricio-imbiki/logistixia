<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Chauffeur;
use App\Models\Trajet;
use App\Models\Depense;
use App\Models\Marchandise;
use App\Models\Revenu;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// class DashboardController extends Controller
// {
//     /**
//      * Display the dashboard with logistics statistics.
//      */
//     public function index(Request $request)
//     {
//         // Date range filter (default: last 30 days)
//         $startDate = $request->query('start_date', Carbon::today()->subDays(30)->toDateString());
//         $endDate = $request->query('end_date', Carbon::today()->toDateString());

//         // Validate dates
//         try {
//             $start = Carbon::parse($startDate)->startOfDay();
//             $end = Carbon::parse($endDate)->endOfDay();
//         } catch (\Exception $e) {
//             Log::warning('Invalid date range provided: ' . $e->getMessage());
//             $start = Carbon::today()->subDays(30)->startOfDay();
//             $end = Carbon::today()->endOfDay();
//             $startDate = $start->toDateString();
//             $endDate = $end->toDateString();
//         }

//         // Statistics
//         $stats = [
//             'total_camions' => Camion::count(),
//             'total_chauffeurs' => Chauffeur::count(),
//             'total_revenue' => Trajet::whereBetween('date_depart', [$start, $end])
//                 ->join('marchandises', 'trajets.id', '=', 'marchandises.trajet_id')
//                 ->where('marchandises.statut', 'livree')
//                 ->sum('marchandises.valeur_estimee'),

//             'total_depenses' => Depense::whereBetween('dep_date', [$start, $end])->sum('montant'),
//             'trips_by_status' => [
//                 'chargee' => Marchandise::where('statut', 'chargee')
//                     ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
//                     ->count(),
//                 'en_transit' => Marchandise::where('statut', 'en_transit')
//                     ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
//                     ->count(),
//                 'livree' => Marchandise::where('statut', 'livree')
//                     ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
//                     ->count(),
//                 'retour' => Marchandise::where('statut', 'retour')
//                     ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
//                     ->count(),
//             ],
//             'active_trucks' => Camion::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
//             'active_drivers' => Chauffeur::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
//             'total_trips' => Trajet::whereBetween('date_depart', [$start, $end])->count(),
//         ];

//         // Recent activities (last 5 trips)
//         $recent_activities = Trajet::with(['marchandises', 'chauffeur', 'camion', 'itineraire'])
//             ->whereBetween('date_depart', [$start, $end])
//             ->orderBy('date_depart', 'desc')
//             ->take(5)
//             ->get();

//         // Chart data (daily revenue)
//         $chart_data = [
//             'revenue' => [],
//             'labels' => [],
//         ];
//         $current = $start->copy();
//         while ($current <= $end) {
//             $chart_data['labels'][] = $current->format('d/m');
//             $chart_data['revenue'][] = Revenu::whereDate('date_encaisse', $current)
//                 ->join('marchandises', 'revenus.marchandise_id', '=', 'marchandises.id')
//                 ->where('marchandises.statut', 'livree')
//                 ->sum('revenus.montant');
//             $current->addDay();
//         }

//         // Chart data (monthly expenses)
//         $expense_data = [
//             'expenses' => [],
//             'labels' => [],
//         ];
//         $current = $start->copy()->startOfMonth();
//         while ($current <= $end) {
//             $expense_data['labels'][] = $current->format('M Y');
//             $expense_data['expenses'][] = Depense::whereMonth('dep_date', $current->month)
//                 ->whereYear('dep_date', $current->year)
//                 ->sum('montant');
//             $current->addMonth();
//         }

//         if ($request->ajax()) {
//             return response()->json(compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
//         }

//         return view('dashboard.index', compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
//     }

//   // 'total_revenue' => Revenu::whereBetween('date_encaisse', [$start, $end])
//             //     ->join('marchandises', 'revenus.marchandise_id', '=', 'marchandises.id')
//             //     ->where('marchandises.statut', 'livree')
//             //     ->sum('revenus.montant'),


//     // public function index(Request $request)
//     // {
//     //     // Date range filter (default: last 30 days)
//     //     $startDate = $request->query('start_date', Carbon::today()->subDays(30)->toDateString());
//     //     $endDate = $request->query('end_date', Carbon::today()->toDateString());

//     //     // Validate dates
//     //     try {
//     //         $start = Carbon::parse($startDate)->startOfDay();
//     //         $end = Carbon::parse($endDate)->endOfDay();
//     //     } catch (\Exception $e) {
//     //         Log::warning('Invalid date range provided: ' . $e->getMessage());
//     //         $start = Carbon::today()->subDays(30)->startOfDay();
//     //         $end = Carbon::today()->endOfDay();
//     //         $startDate = $start->toDateString();
//     //         $endDate = $end->toDateString();
//     //     }

//     //     // Statistics
//     //     $stats = [
//     //         'total_camions' => Camion::count(),
//     //         'total_chauffeurs' => Chauffeur::count(),
//     //         'total_revenue' => Trajet::whereBetween('date_depart', [$start, $end])
//     //             ->join('marchandises', 'trajets.id', '=', 'marchandises.trajet_id')
//     //             ->where('marchandises.statut', 'livree')
//     //             ->sum('marchandises.valeur_estimee'),
//     //         'total_depenses' => Depense::whereBetween('dep_date', [$start, $end])->sum('montant'),
//     //         'trips_by_status' => [
//     //             'chargee' => Marchandise::where('statut', 'chargee')
//     //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
//     //                 ->count(),
//     //             'en_transit' => Marchandise::where('statut', 'en_transit')
//     //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
//     //                 ->count(),
//     //             'livree' => Marchandise::where('statut', 'livree')
//     //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
//     //                 ->count(),
//     //             'retour' => Marchandise::where('statut', 'retour')
//     //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
//     //                 ->count(),
//     //         ],
//     //         'active_trucks' => Camion::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
//     //         'active_drivers' => Chauffeur::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
//     //     ];

//     //     // Recent activities (last 5 trips)
//     //     $recent_activities = Trajet::with(['marchandises', 'chauffeur', 'camion', 'itineraire'])
//     //         ->whereBetween('date_depart', [$start, $end])
//     //         ->orderBy('date_depart', 'desc')
//     //         ->take(5)
//     //         ->get();

//     //     // Chart data (daily revenue)
//     //     $chart_data = [
//     //         'revenue' => [],
//     //         'labels' => [],
//     //     ];
//     //     $current = $start->copy();
//     //     while ($current <= $end) {
//     //         $chart_data['labels'][] = $current->format('d/m');
//     //         $chart_data['revenue'][] = Trajet::whereDate('date_depart', $current)
//     //             ->join('marchandises', 'trajets.id', '=', 'marchandises.trajet_id')
//     //             ->where('marchandises.statut', 'livree')
//     //             ->sum('marchandises.valeur_estimee');
//     //         $current->addDay();
//     //     }

//     //     // Chart data (monthly expenses)
//     //     $expense_data = [
//     //         'expenses' => [],
//     //         'labels' => [],
//     //     ];
//     //     $current = $start->copy()->startOfMonth();
//     //     while ($current <= $end) {
//     //         $expense_data['labels'][] = $current->format('M Y');
//     //         $expense_data['expenses'][] = Depense::whereMonth('date', $current->month)
//     //             ->whereYear('date', $current->year)
//     //             ->sum('montant');
//     //         $current->addMonth();
//     //     }

//     //     if ($request->ajax()) {
//     //         return response()->json(compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
//     //     }

//     //     return view('dashboard.index', compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
//     // }

//     /**
//      * Show the form for creating a new resource.
//      */
//     public function create()
//     {
//         //
//     }

//     /**
//      * Store a newly created resource in storage.
//      */
//     public function store(Request $request)
//     {
//         //
//     }

//     /**
//      * Display the specified resource.
//      */
//     public function show(string $id)
//     {
//         //
//     }

//     /**
//      * Show the form for editing the specified resource.
//      */
//     public function edit(string $id)
//     {
//         //
//     }

//     /**
//      * Update the specified resource in storage.
//      */
//     public function update(Request $request, string $id)
//     {
//         //
//     }

//     /**
//      * Remove the specified resource from storage.
//      */
//     public function destroy(string $id)
//     {
//         //
//     }
// }

// namespace App\Http\Controllers;

// use App\Models\MarchandiseTransportee;
// use App\Models\Marchandise;
// use App\Models\Trajet;
// use App\Models\Client;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Log;
// use Exception;

// class MarchandiseTransporteeController extends Controller
// {
//     protected function validateTransport(Request $request, bool $isMultiple = false): array
//     {
//         if ($isMultiple) {
//             return $request->validate([
//                 'client_id' => ['required', 'exists:clients,id'],
//                 'marchandises' => ['required', 'array', 'min:1'],
//                 'marchandises.*.trajet_id' => ['required', 'exists:trajets,id'],
//                 'marchandises.*.marchandise_id' => ['required', 'exists:marchandises,id'],
//                 'marchandises.*.quantite' => ['required', 'numeric', 'min:0'],
//                 'marchandises.*.poids_kg' => ['nullable', 'numeric', 'min:0'],
//                 'marchandises.*.volume_m3' => ['nullable', 'numeric', 'min:0'],
//                 'marchandises.*.valeur_estimee' => ['nullable', 'numeric', 'min:0'],
//                 'marchandises.*.lieu_livraison' => ['nullable', 'string', 'max:120'],
//                 'marchandises.*.statut' => ['required', 'in:chargee,en_transit,livree,retour'],
//             ]);
//         }

//         return $request->validate([
//             'trajet_id' => ['required', 'exists:trajets,id'],
//             'client_id' => ['required', 'exists:clients,id'],
//             'marchandise_id' => ['required', 'exists:marchandises,id'],
//             'quantite' => ['required', 'numeric', 'min:0'],
//             'poids_kg' => ['nullable', 'numeric', 'min:0'],
//             'volume_m3' => ['nullable', 'numeric', 'min:0'],
//             'valeur_estimee' => ['nullable', 'numeric', 'min:0'],
//             'lieu_livraison' => ['nullable', 'string', 'max:120'],
//             'statut' => ['required', 'in:chargee,en_transit,livree,retour'],
//         ]);
//     }

//     public function index(Request $request)
//     {
//         $query = MarchandiseTransportee::with(['client', 'trajet', 'marchandise']);

//         if ($request->filled('search')) {
//             $query->where('lieu_livraison', 'like', '%' . $request->search . '%');
//         }

//         if ($request->filled('client_id')) {
//             $query->where('client_id', $request->client_id);
//         }

//         if ($request->filled('statut')) {
//             $query->where('statut', $request->statut);
//         }

//         $marchandises = $query->paginate(10);
//         $clients = Client::all();
//         $stats = [
//             'chargee' => MarchandiseTransportee::where('statut', 'chargee')->count(),
//             'en_transit' => MarchandiseTransportee::where('statut', 'en_transit')->count(),
//             'livree' => MarchandiseTransportee::where('statut', 'livree')->count(),
//             'retour' => MarchandiseTransportee::where('statut', 'retour')->count(),
//         ];

//         return view('marchandises_transportees.index', compact('marchandises', 'clients', 'stats'));
//     }

//     public function create()
//     {
//         return view('marchandises_transportees.form', [
//             'marchandiseTransportee' => null,
//             'trajets' => Trajet::with('itineraire')->get(),
//             'clients' => Client::all(),
//             'marchandises' => Marchandise::all(),
//         ]);
//     }

//     public function store(Request $request)
//     {
//         $validated = $this->validateTransport($request, true);

//         try {
//             foreach ($validated['marchandises'] as $data) {
//                 MarchandiseTransportee::create([
//                     'trajet_id' => $data['trajet_id'],
//                     'client_id' => $validated['client_id'],
//                     'marchandise_id' => $data['marchandise_id'],
//                     'quantite' => $data['quantite'],
//                     'poids_kg' => $data['poids_kg'] ?? null,
//                     'volume_m3' => $data['volume_m3'] ?? null,
//                     'valeur_estimee' => $data['valeur_estimee'] ?? null,
//                     'lieu_livraison' => $data['lieu_livraison'] ?? null,
//                     'statut' => $data['statut'],
//                 ]);
//             }

//             return redirect()->route('marchandises-transportees.index')->with('success', 'Marchandises transportées ajoutées.');
//         } catch (Exception $e) {
//             Log::error('Erreur lors de l’ajout : ' . $e->getMessage());
//             return back()->withInput()->withErrors(['error' => 'Erreur lors de l’ajout.']);
//         }
//     }

//     public function edit(MarchandiseTransportee $marchandiseTransportee)
//     {
//         return view('marchandises_transportees.form', [
//             'marchandiseTransportee' => $marchandiseTransportee,
//             'trajets' => Trajet::with('itineraire')->get(),
//             'clients' => Client::all(),
//             'marchandises' => Marchandise::all(),
//         ]);
//     }

//     public function update(Request $request, MarchandiseTransportee $marchandiseTransportee)
//     {
//         $validated = $this->validateTransport($request);

//         try {
//             $marchandiseTransportee->update($validated);
//             return redirect()->route('marchandises-transportees.index')->with('success', 'Mise à jour réussie.');
//         } catch (Exception $e) {
//             Log::error('Erreur MAJ : ' . $e->getMessage());
//             return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour.']);
//         }
//     }

//     public function destroy(MarchandiseTransportee $marchandiseTransportee)
//     {
//         try {
//             $marchandiseTransportee->delete();
//             return redirect()->route('marchandises-transportees.index')->with('success', 'Supprimée avec succès.');
//         } catch (Exception $e) {
//             Log::error('Erreur suppression : ' . $e->getMessage());
//             return back()->withErrors(['error' => 'Erreur lors de la suppression.']);
//         }
//     }
// }



// <x-layouts.app>
//     <!-- Dependencies -->
//     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
//     <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
//     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
//     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tippy.js@6/dist/tippy.css" />
//     <script src="https://cdn.jsdelivr.net/npm/tippy.js@6"></script>
//     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css" />
//     <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
//     <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
//     <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
//     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.5/leaflet.awesome-markers.css">
//     <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.awesome-markers/2.0.5/leaflet.awesome-markers.min.js"></script>
//     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

//     <div class="p-6 bg-gray-100 dark:bg-gray-900 min-h-screen">
//         <!-- Header -->
//         <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
//             <div>
//                 <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Tableau de bord') }}</h1>
//                 <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Aperçu de vos opérations logistiques') }}</p>
//             </div>
//             <div class="relative">
//                 <button id="dateRangeBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 flex items-center gap-2" aria-label="Sélectionner une période">
//                     <x-heroicon-o-calendar class="h-5 w-5" aria-hidden="true" />
//                     <span id="dateRangeText">{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</span>
//                 </button>
//                 <input type="text" id="dateRangePicker" class="hidden" value="{{ $startDate }} to {{ $endDate }}"
//                        aria-label="Sélectionner une plage de dates">
//             </div>
//         </div>

//         <!-- Stats Cards -->
//         <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
//             <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transform transition-transform hover:scale-105"
//                  data-tippy-content="Nombre total de camions dans votre flotte">
//                 <div class="flex items-center justify-between">
//                     <div>
//                         <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Camions') }}</p>
//                         <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1" id="totalCamions">{{ $stats['total_camions'] }}</p>
//                         <p class="text-xs text-blue-500 flex items-center mt-1">
//                             <x-heroicon-o-truck class="h-4 w-4 mr-1" />
//                             {{ $stats['active_trucks'] }} {{ __('actifs') }}
//                         </p>
//                     </div>
//                     <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
//                         <x-heroicon-o-truck class="h-6 w-6 text-blue-500 dark:text-blue-300" />
//                     </div>
//                 </div>
//             </div>
//             <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transform transition-transform hover:scale-105"
//                  data-tippy-content="Nombre total de chauffeurs employés">
//                 <div class="flex items-center justify-between">
//                     <div>
//                         <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Chauffeurs') }}</p>
//                         <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1" id="totalChauffeurs">{{ $stats['total_chauffeurs'] }}</p>
//                         <p class="text-xs text-orange-500 flex items-center mt-1">
//                             <x-heroicon-o-user class="h-4 w-4 mr-1" />
//                             {{ $stats['active_drivers'] }} {{ __('actifs') }}
//                         </p>
//                     </div>
//                     <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
//                         <x-heroicon-o-user class="h-6 w-6 text-orange-500 dark:text-orange-300" />
//                     </div>
//                 </div>
//             </div>
//             <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transform transition-transform hover:scale-105"
//                  data-tippy-content="Revenus totaux encaissés des livraisons sur la période sélectionnée">
//                 <div class="flex items-center justify-between">
//                     <div>
//                         <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Revenus') }}</p>
//                         <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1" id="totalRevenue">{{ number_format($stats['total_revenue'], 0, ',', ' ') }} Ar</p>
//                         <p class="text-xs text-green-500 flex items-center mt-1">
//                             <x-heroicon-o-arrow-up class="h-4 w-4 mr-1" />
//                             {{ $stats['trips_by_status']['livree'] }} {{ __('livraisons') }}
//                         </p>
//                     </div>
//                     <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
//                         <x-heroicon-o-currency-dollar class="h-6 w-6 text-green-500 dark:text-green-300" />
//                     </div>
//                 </div>
//             </div>
//             <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700 transform transition-transform hover:scale-105"
//                  data-tippy-content="Dépenses totales sur la période sélectionnée">
//                 <div class="flex items-center justify-between">
//                     <div>
//                         <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Dépenses') }}</p>
//                         <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1" id="totalDepenses">{{ number_format($stats['total_depenses'], 0, ',', ' ') }} Ar</p>
//                         <p class="text-xs text-red-500 flex items-center mt-1">
//                             <x-heroicon-o-arrow-down class="h-4 w-4 mr-1" />
//                             {{ $stats['total_trips'] }} {{ __('trajets') }}
//                         </p>
//                     </div>
//                     <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
//                         <x-heroicon-o-arrow-trending-down class="h-6 w-6 text-purple-500 dark:text-purple-300" />
//                     </div>
//                 </div>
//             </div>
//         </div>

//         <!-- Charts, Map, and Activity -->
//         <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 flex-1">
//             <!-- Revenue Chart -->
//             <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 lg:col-span-2 h-[400px] flex flex-col">
//                 <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Tendance des revenus') }}</h2>
//                 <div class="flex-1 relative">
//                     <canvas id="revenueChart"></canvas>
//                 </div>
//             </div>

//             <!-- Trip Status Chart -->
//             <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 h-[400px] flex flex-col">
//                 <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Statut des marchandises') }}</h2>
//                 <div class="flex-1 relative">
//                     <canvas id="statusChart"></canvas>
//                 </div>
//             </div>

//             <!-- Map Integration -->
//             <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 lg:col-span-2 h-[400px] flex flex-col">
//                 <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">{{ __('Localisation des camions') }}</h2>
//                 <div id="truckMap" class="flex-1"></div>
//             </div>

//             <!-- Recent Activity -->
//             <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700 h-[400px] flex flex-col">
//                 <div class="flex justify-between items-center mb-4">
//                     <h2 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Activités récentes') }}</h2>
//                     <select id="activityFilter" class="px-2 py-1 bg-gray-100 dark:bg-gray-700 rounded-md text-sm" aria-label="Filtrer les activités">
//                         <option value="all">{{ __('Toutes') }}</option>
//                         <option value="livree">{{ __('Livrée') }}</option>
//                         <option value="en_transit">{{ __('En transit') }}</option>
//                         <option value="chargee">{{ __('Chargée') }}</option>
//                         <option value="retour">{{ __('Retour') }}</option>
//                     </select>
//                 </div>
//                 <div id="activityFeed" class="flex-1 space-y-4 overflow-y-auto pr-2">
//                     @forelse ($recent_activities as $activity)
//                         <div class="flex items-start gap-3 border-b border-gray-200 dark:border-gray-700 pb-3 animate-slide-in" style="animation-delay: {{ $loop->index * 0.1 }}s" role="listitem" tabindex="0">
//                             <div class="bg-indigo-50 dark:bg-indigo-900/50 p-2 rounded-full">
//                                 <i class="fas fa-clipboard-list h-5 w-5 text-indigo-500 dark:text-indigo-300"></i>
//                             </div>
//                             <div class="flex-1">
//                                 <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $activity->itineraire?->lieu_depart ?? '-' }} → {{ $activity->itineraire?->lieu_arrivee ?? '-' }}</p>
//                                 <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $activity->chauffeur?->nom ?? '-' }} | {{ $activity->camion?->accreditation ?? '-' }} | {{ \Carbon\Carbon::parse($activity->date_depart)->format('d/m/Y') }}</p>
//                                 <p class="text-xs mt-1">
//                                     Statut: <span class="@if($activity->marchandises->first()?->statut === 'livree') text-green-600 dark:text-green-400 @elseif($activity->marchandises->first()?->statut === 'en_transit') text-yellow-600 dark:text-yellow-400 @elseif($activity->marchandises->first()?->statut === 'retour') text-red-600 dark:text-red-400 @else text-indigo-600 dark:text-indigo-400 @endif">{{ ucfirst($activity->marchandises->first()?->statut ?? '-') }}</span>
//                                 </p>
//                             </div>
//                         </div>
//                     @empty
//                         <p class="text-gray-500 dark:text-gray-400 text-center text-sm mt-4">{{ __('Aucune activité récente') }}</p>
//                     @endforelse
//                 </div>
//                 <button id="loadMore" class="mt-4 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 text-sm">{{ __('Charger plus') }}</button>
//             </div>
//         </div>

//         <!-- Loading Spinner -->
//         <div id="loadingSpinner" class="fixed inset-0 bg-black/50 hidden flex items-center justify-center z-50">
//             <svg class="w-8 h-8 animate-spin text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
//                 <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
//                 <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
//             </svg>
//         </div>
//     </div>

//     <style>
//         @keyframes slideIn { from { opacity: 0; transform: translateX(-10px); } to { opacity: 1; transform: translateX(0); } }
//         .animate-slide-in { animation: slideIn 0.3s ease-out forwards; }
//         #activityFeed::-webkit-scrollbar { width: 6px; }
//         #activityFeed::-webkit-scrollbar-track { background: transparent; }
//         #activityFeed::-webkit-scrollbar-thumb { background: #9CA3AF; border-radius: 3px; }
//         #activityFeed::-webkit-scrollbar-thumb:hover { background: #6B7280; }
//         canvas { max-height: 100% !important; width: 100% !important; }
//         [data-tippy-root] { font-size: 0.875rem; border-radius: 0.375rem; }
//         .dark [data-tippy-root] { background-color: #1F2937; }
//         #truckMap { border-radius: 8px; }
//     </style>

//     <script>
//         // Status class mapping
//         const statusClasses = {
//             'livree': 'text-green-600 dark:text-green-400',
//             'en_transit': 'text-yellow-600 dark:text-yellow-400',
//             'retour': 'text-red-600 dark:text-red-400',
//             'chargee': 'text-indigo-600 dark:text-indigo-400',
//             'default': 'text-indigo-600 dark:text-indigo-400'
//         };

//         // Initialize Flatpickr
//         flatpickr('#dateRangePicker', {
//             mode: 'range',
//             dateFormat: 'Y-m-d',
//             defaultDate: ['{{ $startDate }}', '{{ $endDate }}'],
//             onChange: function(selectedDates) {
//                 if (selectedDates.length === 2) {
//                     const start = selectedDates[0].toLocaleDateString('fr-FR');
//                     const end = selectedDates[1].toLocaleDateString('fr-FR');
//                     document.getElementById('dateRangeText').textContent = `${start} - ${end}`;
//                     fetchData(selectedDates[0].toISOString().split('T')[0], selectedDates[1].toISOString().split('T')[0]);
//                 }
//             }
//         });

//         document.getElementById('dateRangeBtn').addEventListener('click', () => {
//             document.getElementById('dateRangePicker')._flatpickr.open();
//         });

//         // Fetch Dashboard Data
//         async function fetchData(startDate, endDate, filter = 'all') {
//             document.getElementById('loadingSpinner').classList.remove('hidden');
//             try {
//                 const response = await fetch(`{{ route('dashboard') }}?start_date=${startDate}&end_date=${endDate}&filter=${filter}`, {
//                     headers: { 'X-Requested-With': 'XMLHttpRequest' }
//                 });
//                 const data = await response.json();
//                 updateDashboard(data);
//                 updateMap(data.trucks);
//             } catch (error) {
//                 Toastify({ text: 'Erreur lors du chargement', duration: 3000, backgroundColor: '#EF4444' }).showToast();
//             } finally {
//                 document.getElementById('loadingSpinner').classList.add('hidden');
//             }
//         }

//         // Update Dashboard
//         function updateDashboard(data) {
//             document.getElementById('totalCamions').textContent = data.stats.total_camions;
//             document.getElementById('totalChauffeurs').textContent = data.stats.total_chauffeurs;
//             document.getElementById('totalRevenue').textContent = new Intl.NumberFormat('fr-FR').format(data.stats.total_revenue) + ' Ar';
//             document.getElementById('totalDepenses').textContent = new Intl.NumberFormat('fr-FR').format(data.stats.total_depenses) + ' Ar';

//             revenueChart.data.labels = data.chart_data.labels;
//             revenueChart.data.datasets[0].data = data.chart_data.revenue;
//             revenueChart.update();

//             statusChart.data.datasets[0].data = [
//                 data.stats.trips_by_status.chargee,
//                 data.stats.trips_by_status.en_transit,
//                 data.stats.trips_by_status.livree,
//                 data.stats.trips_by_status.retour
//             ];
//             statusChart.update();

//             const activityFeed = document.getElementById('activityFeed');
//             activityFeed.innerHTML = data.recent_activities.map((a, i) => {
//                 const status = a.marchandises[0]?.statut ?? 'default';
//                 const className = statusClasses[status] || statusClasses['default'];
//                 return `
//                     <div class="flex items-start gap-3 border-b border-gray-200 dark:border-gray-700 pb-3 animate-slide-in" style="animation-delay: ${i * 0.1}s" role="listitem" tabindex="0">
//                         <div class="bg-indigo-50 dark:bg-indigo-900/50 p-2 rounded-full">
//                             <i class="fas fa-clipboard-list h-5 w-5 text-indigo-500 dark:text-indigo-300"></i>
//                         </div>
//                         <div class="flex-1">
//                             <p class="text-sm font-medium text-gray-900 dark:text-white">${a.itineraire?.lieu_depart ?? '-'} → ${a.itineraire?.lieu_arrivee ?? '-'}</p>
//                             <p class="text-xs text-gray-500 dark:text-gray-400">${a.chauffeur?.nom ?? '-'} | ${a.camion?.accreditation ?? '-'} | ${new Date(a.date_depart).toLocaleDateString('fr-FR')}</p>
//                             <p class="text-xs">Statut: <span class="${className}">${a.marchandises[0]?.statut ?? '-'}</span></p>
//                         </div>
//                     </div>
//                 `;
//             }).join('');
//         }

//         // Charts Initialization
//         const revenueChart = new Chart(document.getElementById('revenueChart'), {
//             type: 'line',
//             data: {
//                 labels: @json($chart_data['labels']),
//                 datasets: [{
//                     label: 'Revenus (Ar)',
//                     data: @json($chart_data['revenue']),
//                     borderColor: '#4F46E5',
//                     fill: true,
//                     tension: 0.4
//                 }]
//             },
//             options: {
//                 responsive: true,
//                 maintainAspectRatio: false,
//                 scales: { y: { beginAtZero: true } }
//             }
//         });

//         const statusChart = new Chart(document.getElementById('statusChart'), {
//             type: 'doughnut',
//             data: {
//                 labels: ['Chargée', 'En transit', 'Livrée', 'Retour'],
//                 datasets: [{
//                     data: [
//                         {{ $stats['trips_by_status']['chargee'] }},
//                         {{ $stats['trips_by_status']['en_transit'] }},
//                         {{ $stats['trips_by_status']['livree'] }},
//                         {{ $stats['trips_by_status']['retour'] }}
//                     ],
//                     backgroundColor: ['#4F46E5', '#F59E0B', '#10B981', '#EF4444']
//                 }]
//             },
//             options: {
//                 responsive: true,
//                 maintainAspectRatio: false,
//                 plugins: { legend: { position: 'bottom' } }
//             }
//         });

//         // Map Initialization
//         const map = L.map('truckMap').setView([-18.1492, 49.40234], 12); // Center on Toamasina, Madagascar
//         L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
//             attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
//         }).addTo(map);

//         let truckMarkers = [];

//         function updateMap(trucks) {
//             // Clear existing markers
//             truckMarkers.forEach(marker => map.removeLayer(marker));
//             truckMarkers = [];

//             // Add new markers for trucks
//             trucks.forEach(truck => {
//                 const marker = L.AwesomeMarkers.icon({
//                     icon: 'truck',
//                     prefix: 'fa',
//                     markerColor: truck.status === 'en_transit' ? 'blue' : truck.status === 'livree' ? 'green' : 'red'
//                 });

//                 const truckMarker = L.marker([truck.latitude, truck.longitude], { icon: marker })
//                     .addTo(map)
//                     .bindPopup(`
//                         <b>Camion: ${truck.accreditation}</b><br>
//                         Chauffeur: ${truck.chauffeur?.nom ?? '-'}<br>
//                         Statut: ${truck.status}<br>
//                         Dernière mise à jour: ${new Date(truck.last_updated).toLocaleString('fr-FR')}
//                     `);
//                 truckMarkers.push(truckMarker);
//             });

//             // Adjust map bounds to fit all markers if there are any
//             if (truckMarkers.length > 0) {
//                 const group = new L.featureGroup(truckMarkers);
//                 map.fitBounds(group.getBounds(), { padding: [50, 50] });
//             }
//         }

//         // Fetch initial truck data
//         fetchData('{{ $startDate }}', '{{ $endDate }}');

//         // Tooltips
//         tippy('[data-tippy-content]', { theme: 'light', animation: 'scale' });

//         // Activity Filter
//         document.getElementById('activityFilter').addEventListener('change', (e) => {
//             const filter = e.target.value;
//             fetchData('{{ $startDate }}', '{{ $endDate }}', filter);
//         });

//         // Load More
//         document.getElementById('loadMore').addEventListener('click', () => {
//             Toastify({ text: 'Chargement des activités supplémentaires', duration: 2000, backgroundColor: '#4F46E5' }).showToast();
//             // Add pagination logic here
//         });
//     </script>
// </x-layouts.app>
