<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Piece extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom_piece', 'ref_fournisseur', 'quantite', 'prix_achat', 'seuil_alerte'
    ];

    public function mouvements()
    {
        return $this->hasMany(MouvementStock::class);
    }
}
