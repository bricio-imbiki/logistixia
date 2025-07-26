<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CamionController;
use App\Http\Controllers\RemorqueController;
use App\Http\Controllers\ChauffeurController;
use App\Http\Controllers\ItineraireController;
use App\Http\Controllers\TrajetController;
use App\Http\Controllers\MarchandiseController;

use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\PasswordController;
use App\Http\Controllers\Settings\AppearanceController;

// Page d’accueil (tableau de bord)
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Routes CRUD
Route::resources([
    'clients'       => ClientController::class,
    'camions'       => CamionController::class,
    'remorques'     => RemorqueController::class,
    'chauffeurs'    => ChauffeurController::class,
    'itineraires'   => ItineraireController::class,
    'trajets'       => TrajetController::class,
    'marchandises'  => MarchandiseController::class,
]);

// Paramètres utilisateurs (authentifié uniquement)
Route::middleware(['auth'])->group(function () {
    Route::get('settings/profile', [ProfileController::class, 'edit'])->name('settings.profile.edit');
    Route::put('settings/profile', [ProfileController::class, 'update'])->name('settings.profile.update');
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('settings.profile.destroy');

    Route::get('settings/password', [PasswordController::class, 'edit'])->name('settings.password.edit');
    Route::put('settings/password', [PasswordController::class, 'update'])->name('settings.password.update');

    Route::get('settings/appearance', [AppearanceController::class, 'edit'])->name('settings.appearance.edit');
});

// Auth routes (login, logout, etc.)
require __DIR__.'/auth.php';
