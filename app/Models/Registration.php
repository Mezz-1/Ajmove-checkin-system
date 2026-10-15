<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'form_timestamp',
        'nom',
        'prenom',
        'ecole',
        'filiere',
        'email',
        'phone_number',
        'date_de_naissance',
        'ville',
        'quartier',
        'is_present',
    ];
}