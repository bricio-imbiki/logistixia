<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carburant extends Model
{
    use HasFactory;

    protected $table = 'carburants';

    // Colonnes en assignement massif
    protected $fillable = [
       'camion_id',
        'trajet_id',
        'date_achat',
        'quantite_litres',
        'prix_unitaire',
        'prix_total',
        'station',
    ];

    // Casting des types (optionnel mais conseillé)
    protected $casts = [
        'date_achat' => 'date',
        'quantite_litres' => 'float',
        'prix_unitaire' => 'float',
        'prix_total' => 'float',
    ];

    /**
     * Relation : un carburant appartient à un camion
     */
      public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }
}
