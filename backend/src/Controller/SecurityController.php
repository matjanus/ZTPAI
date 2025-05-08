<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\UserRepository;
use App\Repository\RoleRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


use Psr\Log\LoggerInterface;

class SecurityController extends AbstractController
{
    // #[Route('/login', name: 'api_login', methods: ['POST'])]
    // public function login(Request $request): JsonResponse
    // {
    //     $data = json_decode($request->getContent(), true);
    //     $username = $data['username'] ?? null;
    //     $password = $data['password'] ?? null;

    //     if (!$email || !$password) {
    //         return $this->json(['error' => 'Invalid credentials'], 400);
    //     }

    //     return $this->json(['message' => 'Login successful', 'email' => $email], 200);
    // }

    #[Route('/register', name: 'api_register', methods: ['POST'])]
    public function register(
        Request $request,
        UserRepository $userRepository,
        RoleRepository $roleRepository,
        UserPasswordHasherInterface $passwordHasher,
        LoggerInterface $logger
    ): JsonResponse {
        try {
            $data = json_decode($request->getContent(), true);

            $role = $roleRepository->findOneBy(['roleName' => 'USER']);
            if (!$role) {
                return new JsonResponse(['error' => 'Invalid role'], 400);
            }

            $user = $userRepository->createUser(
                $data['username'],
                $data['password'],
                $data['email'],
                $role,
                $passwordHasher
            );

            return new JsonResponse(['id' => $user->getId(), 'username' => $user->getUsername()]);
        } catch (\Throwable $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        }
    }



// #[Route('/login', name: 'api_login', methods: ['POST'])]
// public function login(
//     Request $request,
//     UserRepository $userRepository,
//     UserPasswordHasherInterface $passwordHasher
// ): JsonResponse {
//     $data = json_decode($request->getContent(), true);
//     $username = $data['username'] ?? null;
//     $password = $data['password'] ?? null;

//     if (!$username || !$password) {
//         return new JsonResponse(['error' => 'Missing credentials'], 400);
//     }

//     $user = $userRepository->findOneBy(['username' => $username]);

//     if (!$user || !$passwordHasher->isPasswordValid($user, $password)) {
//         return new JsonResponse(['error' => 'Invalid credentials'], 401);
//     }

//     // TODO: opcjonalnie: wygeneruj token JWT lub sesję
//     return new JsonResponse([
//         'message' => 'Login successful',
//         'username' => $user->getUsername(),
//         'role' => $user->getRoles(),
//         'email' => $user->getEmail()
//     ]);
// }

    
}
