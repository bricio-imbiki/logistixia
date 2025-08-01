<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    use HasFactory;


    protected $fillable = [
        'trajet_id', 'client_id', 'marchandise_id', 'quantite', 'poids_kg', 'volume_m3',
        'valeur_estimee', 'lieu_livraison', 'statut'
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function marchandise() {
        return $this->belongsTo(Marchandise::class);
    }

    public function revenus() {
        return $this->hasMany(Revenu::class);
    }

    public function trajet() {
        return $this->belongsTo(Trajet::class);
    }
}

