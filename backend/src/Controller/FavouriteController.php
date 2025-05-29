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

#[Route('/api/favourites')]
class FavouriteController extends AbstractController
{
    public function __construct(private FavouriteService $favouriteService) {}

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

    #[Route('/{id}/check', name: 'check_favourite', methods: ['GET'])]
    public function isFavourite(
        #[CurrentUser] User $user,
        int $id
    ): JsonResponse {
        $isFavourite = $this->favouriteService->isFavourite($user, $id);
        return $this->json(['favourite' => $isFavourite]);
    }
}
