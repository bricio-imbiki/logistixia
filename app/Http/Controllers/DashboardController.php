<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Chauffeur;
use App\Models\Trajet;
use App\Models\Depense;
use App\Models\Revenu;
use App\Models\Transport;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Affiche les statistiques logistiques du tableau de bord.
     */
   public function index(Request $request)
{
    // 📅 Define date range (default: last 30 days)
    $startDate = $request->query('start_date', Carbon::today()->subDays(30)->toDateString());
    $endDate = $request->query('end_date', Carbon::today()->toDateString());

    // ✅ Validate and parse dates
    try {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();
    } catch (\Exception $e) {
        Log::warning('Invalid date range: ' . $e->getMessage());
        $start = Carbon::today()->subDays(30)->startOfDay();
        $end = Carbon::today()->endOfDay();
        $startDate = $start->toDateString();
        $endDate = $end->toDateString();
    }

    // 📊 Statistics
    $stats = [
        'total_camions' => Camion::count(),
        'total_chauffeurs' => Chauffeur::count(),
        'total_revenue' => Trajet::whereBetween('date_depart', [$start, $end])
            ->join('transports', 'trajets.id', '=', 'transports.trajet_id')
            ->where('transports.statut', 'livree')
            ->sum('transports.valeur_estimee'),

        'total_depenses' => Depense::whereBetween('dep_date', [$start, $end])->sum('montant'),

        'trips_by_status' => collect(['chargee', 'en_transit', 'livree', 'retour'])->mapWithKeys(function ($status) use ($start, $end) {
            return [
                $status => Transport::where('statut', $status)
                    ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
                    ->count()
            ];
        })->toArray(),

        'active_trucks' => Camion::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
        'active_drivers' => Chauffeur::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
        'total_trips' => Trajet::whereBetween('date_depart', [$start, $end])->count(),
    ];

    // 🕵️ Recent activities
    $recent_activities = Trajet::with(['transports', 'chauffeur', 'camion', 'itineraire'])
        ->whereBetween('date_depart', [$start, $end])
        ->orderBy('date_depart', 'desc')
        ->take(5)
        ->get();

    // 📈 Chart data (daily revenue)
    $chart_data = [
        'revenue' => [],
        'labels' => [],
    ];

    $current = $start->copy();
    while ($current <= $end) {
        $chart_data['labels'][] = $current->format('d/m');
        $chart_data['revenue'][] = Revenu::whereDate('date_encaisse', $current)
            ->join('transports', 'revenus.transport_id', '=', 'transports.id')
            ->where('transports.statut', 'livree')
            ->sum('revenus.montant');
        $current->addDay();
    }

    // 📉 Monthly expense chart
    $expense_data = [
        'expenses' => [],
        'labels' => [],
    ];

    $current = $start->copy()->startOfMonth();
    while ($current <= $end) {
        $expense_data['labels'][] = $current->format('M Y');
        $expense_data['expenses'][] = Depense::whereBetween('dep_date', [
            $current->copy()->startOfMonth(),
            $current->copy()->endOfMonth()
        ])->sum('montant');
        $current->addMonth();
    }

    // 🚚 Truck status mapping
    $trucks = Camion::with([
            'trajets.chauffeur',
            'trajets.itineraire',
            'trajets.transports'
        ])
        ->whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
        ->get()
        ->map(function ($camion) {
            $latest_trajet = $camion->trajets->sortByDesc('date_depart')->first();

            // Safely get first transport
            $first_transport = $latest_trajet?->transports instanceof \Illuminate\Support\Collection
                ? $latest_trajet->transports->first()
                : null;

            return [
                'accreditation' => $camion->accreditation ?? 'N/A',
                'chauffeur' => $latest_trajet?->chauffeur ?? null,
                'status' => $first_transport?->statut ?? 'unknown',
                'latitude' => $latest_trajet?->itineraire?->latitude_arrivee ?? -18.1492,
                'longitude' => $latest_trajet?->itineraire?->longitude_arrivee ?? 49.40234,
                'last_updated' => optional($latest_trajet?->updated_at)->toDateTimeString() ?? now()->toDateTimeString(),
            ];
        })->toArray();

    // 🔁 AJAX or Blade response
    $payload = compact(
        'stats',
        'recent_activities',
        'chart_data',
        'expense_data',
        'startDate',
        'endDate',
        'trucks'
    );

    return $request->ajax()
        ? response()->json($payload)
        : view('dashboard.index', $payload);
}


    //   public function index(Request $request)
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
    //             ->join('transports', 'trajets.id', '=', 'transports.trajet_id')
    //             ->where('transports.statut', 'livree')
    //             ->sum('transports.valeur_estimee'),

    //         'total_depenses' => Depense::whereBetween('dep_date', [$start, $end])->sum('montant'),
    //         'trips_by_status' => [
    //             'chargee' => Transport::where('statut', 'chargee')
    //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
    //                 ->count(),
    //             'en_transit' => Transport::where('statut', 'en_transit')
    //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
    //                 ->count(),
    //             'livree' => Transport::where('statut', 'livree')
    //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
    //                 ->count(),
    //             'retour' => Transport::where('statut', 'retour')
    //                 ->whereHas('trajet', fn($q) => $q->whereBetween('date_depart', [$start, $end]))
    //                 ->count(),
    //         ],
    //         'active_trucks' => Camion::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
    //         'active_drivers' => Chauffeur::whereHas('trajets', fn($q) => $q->whereBetween('date_depart', [$start, $end]))->count(),
    //         'total_trips' => Trajet::whereBetween('date_depart', [$start, $end])->count(),
    //     ];

    //     // Recent activities (last 5 trips)
    //     $recent_activities = Trajet::with(['transports', 'chauffeur', 'camion', 'itineraire'])
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
    //         $chart_data['revenue'][] = Revenu::whereDate('date_encaisse', $current)
    //             ->join('transports', 'revenus.transport_id', '=', 'transports.id')
    //             ->where('transports.statut', 'livree')
    //             ->sum('revenus.montant');
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
    //         $expense_data['expenses'][] = Depense::whereMonth('dep_date', $current->month)
    //             ->whereYear('dep_date', $current->year)
    //             ->sum('montant');
    //         $current->addMonth();
    //     }

    //     if ($request->ajax()) {
    //         return response()->json(compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
    //     }

    //     return view('dashboard.index', compact('stats', 'recent_activities', 'chart_data', 'expense_data', 'startDate', 'endDate'));
    // }

}
