<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'raison_sociale', 'contact', 'telephone', 'email', 'adresse', 'type_client'
    ];

    public function marchandises()
    {
        return $this->hasMany(Marchandise::class);
    }
}
