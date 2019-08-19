<?php
namespace App\Controller\Api;

class RecipesController extends AuthApiController
{
    public function index()
    {
        $this->set([
            'recipes' => [
                'a' => 1,
                'b' => 2
            ],
            '_serialize' => ['recipes'],
        ]);
    }
}
