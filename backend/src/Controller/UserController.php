<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Service\UserService;
use App\Entity\User;

class UserController extends AbstractController
{
    #[Route('/api/users', name: 'get_users', methods: ['GET'])]
    public function getUsers(): JsonResponse
    {
        $users = [
            ['id' => 1, 'name' => 'MGRkowal', 'email' =>
                'janKowal@gmail.com'],
            ['id' => 2, 'name' => 'alamakota', 'email' =>
                'alaKota@gmail.com'],
            ];
        return $this->json($users);
    }

    #[Route('/api/me', name: 'api_me', methods: ['GET'])]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function me(): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'User unauthorized.'], 401);
        }

        return $this->json([
            'id' => $user->getId(),
            'username' => $user->getUserIdentifier(),
        ]);
    }

    #[Route('/api/user/{id}', name: 'get_user_info', methods: ['GET'])]
    public function getUserInfo(int $id, UserService $userService): JsonResponse
    {
        try {
            $username = $userService->getUsername($id);

            return $this->json(['username' => $username]);
        } catch (\InvalidArgumentException $e) {
            return $this->json(['error' => $e->getMessage()], 404);
        }
    }

    #[Route('/api/user/delete', name: 'user_delete', methods: ['DELETE'])]
    public function deleteAccount(
        #[CurrentUser] User $user,
        UserService $userService
    ): JsonResponse {
        try {
            $userService->deleteUser($user);
            return new JsonResponse(null, 204);
        } catch (\Throwable $e) {
            return $this->json(['error' => $e->getMessage()], 400);
        }
    }


}