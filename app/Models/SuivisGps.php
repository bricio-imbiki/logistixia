<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SuivisGps extends Model
{
    use HasFactory;

    protected $fillable = [
        'camion_id', 'latitude', 'longitude',
        'vitesse_kmh', 'niveau_carburant', 'event_time'
    ];

    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }
}
