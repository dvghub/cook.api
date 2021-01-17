<?php
declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RecipeController {
    public function create(Request $request): Response {
        $json = $request->request->get('recipe');

        return new Response('Created');
    }
}
