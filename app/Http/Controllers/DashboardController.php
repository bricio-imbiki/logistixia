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

class DashboardController extends Controller
{
    /**
     * Display the dashboard with logistics statistics.
     */
    public function index(Request $request)
    {
        // Date range filter (default: last 30 days)
        $startDate = $request->query('start_date', Carbon::today()->subDays(30)->toDateString());
        $endDate = $request->query('end_date', Carbon::today()->toDateString());

        // Validate dates
        try {
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();
        } catch (\Exception $e) {
            Log::warning('Invalid date range provided: ' . $e->getMessage());
            $start = Carbon::today()->subDays(30)->startOfDay();
            $end = Carbon::today()->endOfDay();
            $startDate = $start->toDateString();
            $endDate = $end->toDateString();
        }

        // Statistics
        $stats = [
            'total_camions' => Camion::count(),
            'total_chauffeurs' => Chauffeur::count(),
            'total_revenue' => Trajet::whereBetween('date_depart', [$start, $end])
                ->join('marchandises', 'trajets.id', '=', 'marchandises.trajet_id')
                ->where('marchandises.statut', 'livree')
                ->sum('marchandises.valeur_estimee'),

            'total_depenses' => Depense::whereBetween('dep_date', [$start, $end])->sum('montant'),
            'trips_by_status' => [
                'chargee' => Marchandise::where('statut', 'chargee')
                    ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
                    ->count(),
                'en_transit' => Marchandise::where('statut', 'en_transit')
                    ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
                    ->count(),
                'livree' => Marchandise::where('statut', 'livree')
                    ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
                    ->count(),
                'retour' => Marchandise::where('statut', 'retour')
                    ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
                    ->count(),
            ],
            'active_trucks' => Camion::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
            'active_drivers' => Chauffeur::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
            'total_trips' => Trajet::whereBetween('date_depart', [$start, $end])->count(),
        ];

        // Recent activities (last 5 trips)
        $recent_activities = Trajet::with(['marchandises', 'chauffeur', 'camion', 'itineraire'])
            ->whereBetween('date_depart', [$start, $end])
            ->orderBy('date_depart', 'desc')
            ->take(5)
            ->get();

        // Chart data (daily revenue)
        $chart_data = [
            'revenue' => [],
            'labels' => [],
        ];
        $current = $start->copy();
        while ($current <= $end) {
            $chart_data['labels'][] = $current->format('d/m');
            $chart_data['revenue'][] = Revenu::whereDate('date_encaisse', $current)
                ->join('marchandises', 'revenus.marchandise_id', '=', 'marchandises.id')
                ->where('marchandises.statut', 'livree')
                ->sum('revenus.montant');
            $current->addDay();
        }

        // Chart data (monthly expenses)
        $expense_data = [
            'expenses' => [],
            'labels' => [],
        ];
        $current = $start->copy()->startOfMonth();
        while ($current <= $end) {
            $expense_data['labels'][] = $current->format('M Y');
            $expense_data['expenses'][] = Depense::whereMonth('dep_date', $current->month)
                ->whereYear('dep_date', $current->year)
                ->sum('montant');
            $current->addMonth();
        }

        if ($request->ajax()) {
            return response()->json(compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
        }

        return view('dashboard.index', compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
    }

  // 'total_revenue' => Revenu::whereBetween('date_encaisse', [$start, $end])
            //     ->join('marchandises', 'revenus.marchandise_id', '=', 'marchandises.id')
            //     ->where('marchandises.statut', 'livree')
            //     ->sum('revenus.montant'),


    // public function index(Request $request)
    // {
    //     // Date range filter (default: last 30 days)
    //     $startDate = $request->query('start_date', Carbon::today()->subDays(30)->toDateString());
    //     $endDate = $request->query('end_date', Carbon::today()->toDateString());

    //     // Validate dates
    //     try {
    //         $start = Carbon::parse($startDate)->startOfDay();
    //         $end = Carbon::parse($endDate)->endOfDay();
    //     } catch (\Exception $e) {
    //         Log::warning('Invalid date range provided: ' . $e->getMessage());
    //         $start = Carbon::today()->subDays(30)->startOfDay();
    //         $end = Carbon::today()->endOfDay();
    //         $startDate = $start->toDateString();
    //         $endDate = $end->toDateString();
    //     }

    //     // Statistics
    //     $stats = [
    //         'total_camions' => Camion::count(),
    //         'total_chauffeurs' => Chauffeur::count(),
    //         'total_revenue' => Trajet::whereBetween('date_depart', [$start, $end])
    //             ->join('marchandises', 'trajets.id', '=', 'marchandises.trajet_id')
    //             ->where('marchandises.statut', 'livree')
    //             ->sum('marchandises.valeur_estimee'),
    //         'total_depenses' => Depense::whereBetween('dep_date', [$start, $end])->sum('montant'),
    //         'trips_by_status' => [
    //             'chargee' => Marchandise::where('statut', 'chargee')
    //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
    //                 ->count(),
    //             'en_transit' => Marchandise::where('statut', 'en_transit')
    //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
    //                 ->count(),
    //             'livree' => Marchandise::where('statut', 'livree')
    //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
    //                 ->count(),
    //             'retour' => Marchandise::where('statut', 'retour')
    //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
    //                 ->count(),
    //         ],
    //         'active_trucks' => Camion::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
    //         'active_drivers' => Chauffeur::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
    //     ];

    //     // Recent activities (last 5 trips)
    //     $recent_activities = Trajet::with(['marchandises', 'chauffeur', 'camion', 'itineraire'])
    //         ->whereBetween('date_depart', [$start, $end])
    //         ->orderBy('date_depart', 'desc')
    //         ->take(5)
    //         ->get();

    //     // Chart data (daily revenue)
    //     $chart_data = [
    //         'revenue' => [],
    //         'labels' => [],
    //     ];
    //     $current = $start->copy();
    //     while ($current <= $end) {
    //         $chart_data['labels'][] = $current->format('d/m');
    //         $chart_data['revenue'][] = Trajet::whereDate('date_depart', $current)
    //             ->join('marchandises', 'trajets.id', '=', 'marchandises.trajet_id')
    //             ->where('marchandises.statut', 'livree')
    //             ->sum('marchandises.valeur_estimee');
    //         $current->addDay();
    //     }

    //     // Chart data (monthly expenses)
    //     $expense_data = [
    //         'expenses' => [],
    //         'labels' => [],
    //     ];
    //     $current = $start->copy()->startOfMonth();
    //     while ($current <= $end) {
    //         $expense_data['labels'][] = $current->format('M Y');
    //         $expense_data['expenses'][] = Depense::whereMonth('date', $current->month)
    //             ->whereYear('date', $current->year)
    //             ->sum('montant');
    //         $current->addMonth();
    //     }

    //     if ($request->ajax()) {
    //         return response()->json(compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
    //     }

    //     return view('dashboard.index', compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
    // }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
