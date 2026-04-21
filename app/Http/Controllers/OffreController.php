<?php

namespace App\Http\Controllers;

use App\Models\Offre;
use Illuminate\Http\Request;

class OffreController extends Controller
{
  
    public function index(Request $request)
    {
        $query = Offre::where('actif', true)
                      ->with('recruteur:id,name');

        if ($request->filled('localisation')) {
            $query->where('localisation', 'like', '%' . $request->localisation . '%');
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        return response()->json(
            $query->orderBy('created_at', 'desc')->paginate(10)
        );
    }

   
    public function show(Offre $offre)
    {
        $offre->load('recruteur:id,name');
        return response()->json($offre);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre'        => 'required|string|max:255',
            'description'  => 'required|string',
            'localisation' => 'required|string',
            'type'         => 'required|in:CDI,CDD,stage',
        ]);

        $offre = auth()->user()->offres()->create($data);

        return response()->json($offre, 201);
    }

  
    public function update(Request $request, Offre $offre)
    {
        if ($offre->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $data = $request->validate([
            'titre'        => 'string|max:255',
            'description'  => 'string',
            'localisation' => 'string',
            'type'         => 'in:CDI,CDD,stage',
        ]);

        $offre->update($data);

        return response()->json($offre);
    }

   
    public function destroy(Offre $offre)
    {
        if ($offre->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Accès refusé'
            ], 403);
        }

        $offre->delete();

        return response()->json([
            'message' => 'Offre supprimée'
        ]);
    }
}