<?php

namespace App\Http\Controllers;

use App\Models\Candidature;
use App\Models\Offre;
use App\Events\CandidatureDeposee;
use App\Events\StatutCandidatureMis;
use Illuminate\Http\Request;

class CandidatureController extends Controller
{
    
    public function store(Request $request, Offre $offre)
    {
        if (!$offre->actif) {
            return response()->json([
                'message' => 'Cette offre n\'est plus active'
            ], 422);
        }

        $profil = auth()->user()->profil;

        if (!$profil) {
            return response()->json([
                'message' => 'Créez votre profil d\'abord'
            ], 422);
        }

        $dejaCandidaté = Candidature::where('offre_id', $offre->id)
                                    ->where('profil_id', $profil->id)
                                    ->exists();

        if ($dejaCandidaté) {
            return response()->json([
                'message' => 'Vous avez déjà postulé à cette offre'
            ], 422);
        }

        $data = $request->validate([
            'message' => 'nullable|string'
        ]);

        $candidature = Candidature::create([
            'offre_id'  => $offre->id,
            'profil_id' => $profil->id,
            'message'   => $data['message'] ?? null,
            'statut'    => 'en_attente'
        ]);

        event(new CandidatureDeposee($candidature));

        return response()->json($candidature, 201);
    }


    public function myCandidatures()
    {
        $profil = auth()->user()->profil;

        if (!$profil) {
            return response()->json([
                'message' => 'Profil non trouvé'
            ], 404);
        }

        $candidatures = $profil->candidatures()
                               ->with('offre:id,titre,localisation,type')
                               ->orderBy('created_at', 'desc')
                               ->get();

        return response()->json($candidatures);
    }

   
    public function offreCandidatures(Offre $offre)
    {
        if ($offre->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $candidatures = $offre->candidatures()
                              ->with('profil.user:id,name,email')
                              ->get();

        return response()->json($candidatures);
    }

    public function updateStatut(Request $request, Candidature $candidature)
    {
        if ($candidature->offre->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $data = $request->validate([
            'statut' => 'required|in:en_attente,acceptee,refusee'
        ]);

        $ancienStatut = $candidature->statut;

        $candidature->update(['statut' => $data['statut']]);

        event(new StatutCandidatureMis($candidature, $ancienStatut));

        return response()->json($candidature);
    }
}