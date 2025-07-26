<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Revenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'marchandise_id', 'montant', 'date_encaisse', 'notes'
    ];

    public function marchandise()
    {
        return $this->belongsTo(Marchandise::class);
    }
}

