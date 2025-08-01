<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenu extends Model
{
    use HasFactory;

    protected $fillable = [
        'transport_id',
        'montant',
        'date_encaisse',
        'notes',
    ];

    /**
     * Relation : ce revenu est lié à une marchandise transportée (trajet + client).
     */
 public function transport()
{
    return $this->belongsTo(Transport::class);
}

    /**
     * Accès rapide à la marchandise (via la marchandise transportée).
     */
    public function marchandise()
    {
        return $this->transport?->marchandise;
    }

    /**
     * Accès rapide au client (via la marchandise transportée).
     */
    public function client()
    {
        return $this->transport?->client;
    }

    /**
     * Accès rapide au trajet (via la marchandise transportée).
     */
    public function trajet()
    {
        return $this->transport?->trajet;
    }
}
