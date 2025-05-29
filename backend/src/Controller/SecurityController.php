<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use App\Service\UserService;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use OpenApi\Attributes as OA;


use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{
    #[OA\Post(
        path: "/api/register",
        summary: "Register a new user",
        tags: ["Security"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "username", type: "string", example: "kowal"),
                    new OA\Property(property: "email", type: "string", example: "kowal@pk.com"),
                    new OA\Property(property: "password", type: "string", example: "StrongP@ss123")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "User registered",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "username", type: "string")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validation error or password error",
                content: new OA\JsonContent(
                    oneOf: [
                        new OA\Schema(
                            properties: [
                                new OA\Property(property: "error", type: "string", example: "Old password is incorrect.")
                            ]
                        ),
                        new OA\Schema(
                            properties: [
                                new OA\Property(property: "error", type: "string", example: "The password must contain min. 8 characters and uppercase and lowercase letter.")
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Unexpected error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Exception - Something went wrong")
                    ]
                )
            )
        ]
    )]
    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, UserService $userService, RoleRepository $roleRepository): JsonResponse
    {
        try {
            $data = json_decode($request->getContent(), true);

            if (empty($data['username']) || empty($data['password']) || empty($data['email'])) {
                return new JsonResponse(['error' => 'All fields are required.'], 400);
            }

            $role = $roleRepository->findOneBy(['roleName' => 'USER']);
            if (!$role) {
                return new JsonResponse(['error' => 'Role unknown.'], 400);
            }

            $user = $userService->registerUser(
                $data['username'],
                $data['password'],
                $data['email'],
                $role
            );

            return new JsonResponse([
                'id' => $user->getId(),
                'username' => $user->getUsername()
            ], 201);

        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => get_class($e) . " - " . $e->getMessage()], 500);
        }
    }

    #[OA\Post(
        path: "/api/user/change-password",
        summary: "Change user's password",
        tags: ["Security"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "oldPassword", type: "string", example: "OldP@ss123"),
                    new OA\Property(property: "newPassword", type: "string", example: "NewStrongP@ss456")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 204,
                description: "Password changed successfully"
            ),
            new OA\Response(
                response: 400,
                description: "Password not strong enough",
                content: new OA\JsonContent(
                    oneOf: [
                        new OA\Schema(
                            properties: [
                                new OA\Property(property: "error", type: "string", example: "Old password is incorrect.")
                            ]
                        ),
                        new OA\Schema(
                            properties: [
                                new OA\Property(property: "error", type: "string", example: "The password must contain min. 8 characters and uppercase and lowercase letter.")
                            ]
                        )
                    ]
                )
            )

        ]
    )]
    
    #[Route('/api/user/change-password', name: 'user_change_password', methods: ['POST'])]
    public function changePassword(
        #[CurrentUser] User $user,
        Request $request,
        UserService $userService
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $oldPassword = $data['oldPassword'] ?? '';
        $newPassword = $data['newPassword'] ?? '';

        if (empty($oldPassword) || empty($newPassword)) {
            return $this->json(['error' => 'Both passwords must be provided.'], 400);
        }

        try {
            $userService->changePassword($user, $oldPassword, $newPassword);
            return new JsonResponse(null, 204);
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => 'Could not change the password'], 400);
        }
    }

    
}
