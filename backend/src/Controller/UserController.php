<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use App\Service\UserService;
use App\Entity\User;
use OpenApi\Attributes as OA;

class UserController extends AbstractController
{

    #[OA\Get(
        path: "/api/me",
        summary: "Used to check if user has valid token JWT",
        security: [ ['bearerAuth' => []] ], 
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 200,
                description: "User info",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "username", type: "string")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "User unauthorized.")
                    ]
                )
            )
        ]
    )]
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


    #[OA\Get(
        path: "/api/user/{id}",
        summary: "Get username of user by ID",
        security: [ ['bearerAuth' => []] ], 
        tags: ["User"],
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
                description: "User found",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "username", type: "string")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "User not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "User not found")
                    ]
                )
            )
        ]
    )]
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


    #[OA\Delete(
        path: "/api/user/delete",
        summary: "Delete current logged-in user",
        security: [ ['bearerAuth' => []] ], 
        tags: ["User"],
        responses: [
            new OA\Response(
                response: 204,
                description: "User deleted successfully"
            ),
            new OA\Response(
                response: 400,
                description: "Something went wrong",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Some error occurred")
                    ]
                )
            )
        ]
    )]
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