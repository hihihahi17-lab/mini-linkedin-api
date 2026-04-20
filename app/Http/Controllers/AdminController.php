<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Offre;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function users(Request $request): JsonResponse
    {
        $users = User::query()
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->latest()
            ->paginate(10);

        return response()->json([
            'message' => 'Liste des utilisateurs',
            'data' => $users,
        ]);
    }

    public function deleteUser(User $user): JsonResponse
    {
        $user->delete();

        return response()->json([
            'message' => 'Utilisateur supprimé avec succès',
        ]);
    }

    public function toggleOffre(Request $request, Offre $offre): JsonResponse
    {
        $validated = $request->validate([
            'actif' => ['required', 'boolean'],
        ]);

        $offre->update([
            'actif' => $validated['actif'],
        ]);

        return response()->json([
            'message' => 'Statut de l\'offre mis à jour avec succès',
            'data' => $offre,
        ]);
    }
}