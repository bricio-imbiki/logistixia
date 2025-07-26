<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Chauffeur extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom', 'prenom', 'date_naissance', 'telephone', 'email',
        'adresse', 'permis_num', 'permis_categorie', 'permis_expire',
        'statut', 'date_embauche', 'experience_annees',
        'cin_num', 'apte_medicalement', 'photo_url', 'document_permis'
    ];

    public function trajets()
    {
        return $this->hasMany(Trajet::class);
    }
}
