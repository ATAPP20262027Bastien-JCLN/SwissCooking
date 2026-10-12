<?php

declare(strict_types=1);

namespace BastienJcln\SwissCooking\Controllers;

use BastienJcln\SwissCooking\Models\Ingredient;
use BastienJcln\SwissCooking\Services\ConnexionService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class IngredientController extends BaseController
{
    public function store(Request $request, Response $response): Response
    {
        $user = ConnexionService::connectedUser();

        if ($user === null) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Vous devez être connecté.',
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }

        $data = $request->getParsedBody();

        if (!is_array($data) || $data === []) {
            $rawBody = (string) $request->getBody();
            $data = json_decode($rawBody, true) ?? [];
        }

        $name = trim((string) ($data['name'] ?? ''));
        $description = trim((string) ($data['description'] ?? ''));

        if ($name === '' || strlen($name) > 255) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Le nom est obligatoire et ne doit pas dépasser 255 caractères.',
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(422);
        }

        try {
            $ingredient = new Ingredient();
            $ingredient->name = $name;

            // Your model requires a non-empty description.
            $ingredient->description = $description !== ''
                ? $description
                : 'Aucune description';

            if (!$ingredient->insert()) {
                throw new \RuntimeException('Insert failed');
            }

            $response->getBody()->write(json_encode([
                'success' => true,
                'ingredient' => [
                    'id' => $ingredient->id,
                    'name' => $ingredient->name,
                ],
            ]));

            return $response->withHeader(
                'Content-Type',
                'application/json'
            )->withStatus(201);

        } catch (\PDOException $e) {
            // ingredients.name has a UNIQUE constraint.
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Cet ingrédient existe peut-être déjà.',
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(409);

        } catch (\Throwable $e) {
            $response->getBody()->write(json_encode([
                'success' => false,
                'error' => 'Une erreur est survenue lors de la création.',
            ]));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(500);
        }
    }
}