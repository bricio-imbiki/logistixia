<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Itineraire extends Model
{
    use HasFactory;

    protected $fillable = [
        'lieu_depart', 'lieu_arrivee', 'distance_km', 'duree_estimee_h', 'peage_estime'
    ];

    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }
}
