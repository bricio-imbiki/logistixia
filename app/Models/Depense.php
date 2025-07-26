<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Depense extends Model
{
    use HasFactory;

    protected $fillable = [
        'camion_id', 'trajet_id', 'type', 'montant', 'dep_date', 'notes'
    ];

    public function camion()
    {
        return $this->belongsTo(Camion::class);
    }

    public function trajet()
    {
        return $this->belongsTo(Trajet::class);
    }
}

