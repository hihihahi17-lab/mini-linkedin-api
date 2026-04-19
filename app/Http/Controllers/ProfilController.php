<?php

namespace App\Http\Controllers;

use App\Models\Competence;
use Illuminate\Http\Request;

class ProfilController extends Controller
{
   
    public function store(Request $request)
    {
        $user = auth()->user();

       
        if ($user->profil) {
            return response()->json([
                'message' => 'Vous avez déjà un profil'
            ], 422);
        }

        $data = $request->validate([
            'titre'       => 'required|string',
            'bio'         => 'nullable|string',
            'localisation'=> 'nullable|string',
            'disponible'  => 'boolean'
        ]);

        $profil = $user->profil()->create($data);

        return response()->json($profil, 201);
    }


    public function show()
    {
        $profil = auth()->user()->profil;

        if (!$profil) {
            return response()->json([
                'message' => 'Profil non trouvé'
            ], 404);
        }

       
        $profil->load('competences');

        return response()->json($profil);
    }

  
    public function update(Request $request)
    {
        $profil = auth()->user()->profil;

        if (!$profil) {
            return response()->json([
                'message' => 'Profil non trouvé'
            ], 404);
        }

        $data = $request->validate([
            'titre'        => 'string',
            'bio'          => 'nullable|string',
            'localisation' => 'nullable|string',
            'disponible'   => 'boolean'
        ]);

        $profil->update($data);

        return response()->json($profil);
    }

    
    public function addCompetence(Request $request)
    {
        $profil = auth()->user()->profil;

        if (!$profil) {
            return response()->json([
                'message' => 'Créez votre profil d\'abord'
            ], 422);
        }

        $data = $request->validate([
            'competence_id' => 'required|exists:competences,id',
            'niveau' => 'required|in:débutant,intermédiaire,expert'
        ]);

     
        if ($profil->competences()->where('competence_id', $data['competence_id'])->exists()) {
            return response()->json([
                'message' => 'Compétence déjà ajoutée'
            ], 422);
        }

        $profil->competences()->attach($data['competence_id'], [
            'niveau' => $data['niveau']
        ]);

        return response()->json([
            'message' => 'Compétence ajoutée'
        ]);
    }

    
    public function removeCompetence(Competence $competence)
    {
        $profil = auth()->user()->profil;

        $profil->competences()->detach($competence->id);

        return response()->json([
            'message' => 'Compétence retirée'
        ]);
    }
}