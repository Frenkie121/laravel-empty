<?php

/**
 * @OA\Info(
 *     title="API de gestion des tâches",
 *     version="1.0.0",
 *     description="Documentation de l'API pour la gestion des tâches et des catégories."
 * )
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
}
