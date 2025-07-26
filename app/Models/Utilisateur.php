<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utilisateur extends Model
{
    use HasFactory;

    protected $table = 'utilisateurs';

    protected $fillable = [
        'nom_utilisateur', 'email', 'mot_de_passe', 'role', 'actif', 'last_login'
    ];

    protected $hidden = ['mot_de_passe'];
}
