<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Remorque extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule', 'type', 'capacite_max', 'est_interne',
        'societe_proprietaire', 'photo_url', 'camion_id'
    ];

    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }
}
