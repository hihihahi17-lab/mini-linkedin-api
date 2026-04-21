<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidature extends Model
{
    use HasFactory;

    protected $fillable = [
        'profil_id',
        'offre_id',
        'statut',
    ];

    public function profil()
    {
        return $this->belongsTo(Profil::class);
    }

    public function offre()
    {
        return $this->belongsTo(Offre::class);
    }
}