<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Camion extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule', 'marque', 'modele', 'annee',
        'capacite_kg', 'statut', 'est_interne',
        'societe_proprietaire', 'photo_url'
    ];

    protected $casts = [
        'est_interne' => 'boolean',
    ];

    public function remorques()
    {
        return $this->hasMany(Remorque::class);
    }

    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }

    public function gps()
    {
        return $this->hasMany(SuivisGps::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
    public function carburants()
    {
        return $this->hasMany(Carburant::class);
    }
}
