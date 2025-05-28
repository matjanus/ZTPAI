<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Role;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Validator\PasswordValidator;


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

        if (!PasswordValidator::isValid($newPassword)) {
            throw new \InvalidArgumentException(PasswordValidator::getPasswordInvalidMessage());
        }

        if (!$this->passwordHasher->isPasswordValid($user, $oldPassword)) {
            throw new \RuntimeException('The old password is invalid.');
        }

        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $newPassword)
        );

        $this->em->flush();
    }

    public function registerUser(string $username, string $password, string $email, Role $role): User
    {

        if (!PasswordValidator::isValid($password)) {
            throw new \InvalidArgumentException(PasswordValidator::getPasswordInvalidMessage());
        }

        if ($this->userRepository->findOneBy(['email' => $email])) {
            throw new \InvalidArgumentException('Email is already taken.');
        }

        if ($this->userRepository->findOneBy(['username' => $username])) {
            throw new \InvalidArgumentException('Username is already taken.');
        }

        return $this->userRepository->createUser($username, $password, $email, $role, $this->passwordHasher);
    }
}