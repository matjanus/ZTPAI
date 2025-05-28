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


use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{

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
