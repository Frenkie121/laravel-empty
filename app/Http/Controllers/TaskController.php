<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Illuminate\Http\Response;

/**
 * @OA\Get(
 *     path="/api/taches",
 *     summary="Liste des tâches",
 *     @OA\Parameter(name="titre", in="query", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="statut", in="query", required=false, @OA\Schema(type="string")),
 *     @OA\Parameter(name="categorie_id", in="query", required=false, @OA\Schema(type="integer")),
 *     @OA\Response(response=200, description="Succès")
 * )
 */
class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();
        if ($request->has('titre')) {
            $query->where('titre', 'like', '%' . $request->titre . '%');
        }
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->has('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        return $query->get();
    }

    /**
     * @OA\Post(
     *     path="/api/taches",
     *     summary="Créer une tâche",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"titre", "categorie_id"},
     *             @OA\Property(property="titre", type="string", example="Acheter du pain"),
     *             @OA\Property(property="description", type="string", example="Aller à la boulangerie"),
     *             @OA\Property(property="statut", type="string", example="en_attente"),
     *             @OA\Property(property="date_echeance", type="string", format="date", example="2024-06-01"),
     *             @OA\Property(property="categorie_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Créée")
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'in:en_attente,en_cours,terminee',
            'date_echeance' => 'nullable|date',
            'categorie_id' => 'required|exists:categories,id',
        ]);
        $task = Task::create($validated);
        return response()->json($task, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/taches/{id}",
     *     summary="Afficher une tâche",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Succès")
     * )
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
        return $task;
    }

    /**
     * @OA\Put(
     *     path="/api/taches/{id}",
     *     summary="Mettre à jour une tâche",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="titre", type="string", example="Acheter du pain"),
     *             @OA\Property(property="description", type="string", example="Aller à la boulangerie"),
     *             @OA\Property(property="statut", type="string", example="en_cours"),
     *             @OA\Property(property="date_echeance", type="string", format="date", example="2024-06-01"),
     *             @OA\Property(property="categorie_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Mise à jour")
     * )
     */
    public function update(Request $request, string $id)
    {
        $task = Task::findOrFail($id);
        $validated = $request->validate([
            'titre' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'statut' => 'in:en_attente,en_cours,terminee',
            'date_echeance' => 'nullable|date',
            'categorie_id' => 'sometimes|required|exists:categories,id',
        ]);
        $task->update($validated);
        return response()->json($task);
    }

    /**
     * @OA\Delete(
     *     path="/api/taches/{id}",
     *     summary="Supprimer une tâche",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Supprimée")
     * )
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(null, 204);
    }

    /**
     * @OA\Patch(
     *     path="/api/taches/{id}/lue",
     *     summary="Marquer une tâche comme lue (terminée)",
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Tâche marquée comme lue")
     * )
     */
    public function marqueCommeLue($id)
    {
        $task = Task::findOrFail($id);
        $task->statut = 'terminee';
        $task->save();
        return response()->json($task);
    }
}
