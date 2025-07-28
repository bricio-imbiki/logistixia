<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SuivisGps;
use App\Models\Camion;

class SuiviGpsController extends Controller
{
    public function index(Request $request)
    {
        // On récupère les derniers suivis GPS
        $suivis = SuivisGps::with('camion')
            ->orderBy('event_time', 'desc')
            ->limit(50) // Limiter à 50 points récents
            ->get();

        return view('suivisGps.index', [
            'suivis' => $suivis
        ]);
    }
}
