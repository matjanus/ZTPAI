<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;


class UserService
{

    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) {
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
}