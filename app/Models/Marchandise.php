<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Marchandise extends Model
{
    use HasFactory;

    protected $fillable = [
        'trajet_id', 'client_id', 'description', 'poids_kg', 'volume_m3',
        'valeur_estimee', 'lieu_livraison', 'statut'
    ];

    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function revenus()
    {
        return $this->hasMany(Revenu::class);
    }
}

