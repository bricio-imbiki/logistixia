<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MouvementStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'piece_id', 'type', 'quantite', 'ref_text', 'event_date'
    ];

    public function piece()
    {
        return $this->belongsTo(Piece::class);
    }

    // public function utilisateur()
    // {
    //     return $this->belongsTo(Utilisateur::class, 'user_id');
    // }
}
