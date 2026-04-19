<?php

namespace App\Models;
use App\Models\Profil;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competence extends Model
{
    use HasFactory;

    protected $fillable = ['nom', 'categorie'];

    public function profils()
    {
        return $this->belongsToMany(
            Profil::class,
            'profil_competence'
        )->withPivot('niveau');
    }
}