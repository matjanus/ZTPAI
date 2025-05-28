<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\UserService;

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


}