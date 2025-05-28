<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class UserService
{

    private UserRepository $userRepository;
    

    public function __construct(
        UserRepository $userRepository,
        private EntityManagerInterface $em,
        private UserPasswordHasherInterface $passwordHasher
        ) 
    {
        $this->userRepository = $userRepository;
    }

    public function getUsername(int $id): string
    {
        $username = $this->userRepository->findUsernameById($id);

        if (!$username) {
            throw new \InvalidArgumentException('The user does not exist.');
        }

        return $username;
    }

    public function deleteUser(User $user): void
    {
        $this->em->remove($user);
        $this->em->flush();
    }

    public function changePassword(User $user, string $oldPassword, string $newPassword): void
    {
        if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
            throw new \RuntimeException('The old password is invalid.');
        }

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $newPassword)
        );

        $this->em->flush();
    }
}