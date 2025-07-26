<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Trajet extends Model
{
    use HasFactory;

    protected $fillable = [
        'camion_id', 'remorque_id', 'chauffeur_id', 'itineraire_id',
        'date_depart', 'date_arrivee_etd', 'date_arrivee_eta', 'statut', 'commentaire'
    ];

    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    public function remorque()
    {
        return $this->belongsTo(Remorque::class);
    }

    public function chauffeur()
    {
        return $this->belongsTo(Chauffeur::class);
    }

    public function itineraire()
    {
        return $this->belongsTo(Itineraire::class);
    }

    public function marchandises()
    {
        return $this->hasMany(Marchandise::class);
    }

    public function depenses()
    {
        return $this->hasMany(Depense::class);
    }
}
