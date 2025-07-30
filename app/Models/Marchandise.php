<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marchandise extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'reference',
        'categorie',
        'unite',
        'poids_moyen',
        'tarif_par_defaut',
    ];

    // Une marchandise peut être transportée plusieurs fois
  public function transports() {

    return $this->hasMany(MarchandiseTransportee::class, 'marchandise_id');
}
}
