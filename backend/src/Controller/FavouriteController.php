<?php
namespace App\Controller;

use App\Service\FavouriteService;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OpenApi\Attributes as OA;

#[Route('/api/favourites')]
class FavouriteController extends AbstractController
{
    public function __construct(private FavouriteService $favouriteService) {}

    #[OA\Get(
        path: "/api/favourites",
        summary: "Get all favourite quizzes for the current user",
        tags: ["Favourites"],
        parameters: [
            new OA\Parameter(
                name: "page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", default: 1)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of favourite quizzes",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "name", type: "string"),
                            new OA\Property(property: "owner", type: "string")
                        ]
                    )
                )
            )
        ]
    )]
    #[Route('', name: 'get_favourites', methods: ['GET'])]
    public function getFavourites(
        #[CurrentUser] User $user,
        Request $request
    ): JsonResponse {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10;

        $favs = $this->favouriteService->getFavourites($user, $page, $limit);
        $result = array_map(fn($fav) => [
            'id' => $fav->getQuiz()->getId(),
            'name' => $fav->getQuiz()->getQuizName(),
            'owner' => $fav->getPatron()->getUsername()
        ], $favs);

        return $this->json($result);
    }

    #[OA\Post(
        path: "/api/favourites/{id}",
        summary: "Add quiz to favourites",
        tags: ["Favourites"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Quiz added to favourites",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Added to favourites")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Quiz not found or already favourite",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Quiz not found")
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', name: 'add_favourite', methods: ['POST'])]
    public function addFavourite(
        #[CurrentUser] User $user,
        int $id
    ): JsonResponse {
        try {
            $this->favouriteService->addToFavourites($user, $id);
            return new JsonResponse(['message' => 'Added to favourites'], 201);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[OA\Delete(
        path: "/api/favourites/{id}",
        summary: "Remove quiz from favourites",
        tags: ["Favourites"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Quiz removed from favourites"
            ),
            new OA\Response(
                response: 400,
                description: "Quiz not in favourites",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Quiz not in favourites")
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}', name: 'remove_favourite', methods: ['DELETE'])]
    public function removeFavourite(
        #[CurrentUser] User $user,
        int $id
    ): JsonResponse {
        try {
            $this->favouriteService->removeFromFavourites($user, $id);
            return new JsonResponse(['message' => 'Removed from favourites'], 204);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }

    #[OA\Get(
        path: "/api/favourites/{id}/check",
        summary: "Check if quiz is favourite",
        tags: ["Favourites"],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Favourite check result",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "favourite", type: "boolean", example: true)
                    ]
                )
            )
        ]
    )]
    #[Route('/{id}/check', name: 'check_favourite', methods: ['GET'])]
    public function isFavourite(
        #[CurrentUser] User $user,
        int $id
    ): JsonResponse {
        $isFavourite = $this->favouriteService->isFavourite($user, $id);
        return $this->json(['favourite' => $isFavourite]);
    }
}
