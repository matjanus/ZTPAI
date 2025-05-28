<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;



/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    //    /**
    //     * @return User[] Returns an array of User objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('u.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?User
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function createUser(
        string $username,
        string $plainPassword,
        string $email,
        Role $role,
        UserPasswordHasherInterface $passwordHasher
    ): User {
        // Sprawdzenie, czy użytkownik z takim nazwiskiem już istnieje
        $existingUserByUsername = $this->findOneBy(['username' => $username]);
        if ($existingUserByUsername) {
            throw new \Exception('Username is already taken.');
        }
    
        // Sprawdzenie, czy użytkownik z takim e-mailem już istnieje
        $existingUserByEmail = $this->findOneBy(['email' => $email]);
        if ($existingUserByEmail) {
            throw new \Exception('Email is already taken.');
        }
    
        // Tworzenie nowego użytkownika
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
    
        // Haszowanie hasła
        $hashedPassword = $passwordHasher->hashPassword($user, $plainPassword);
        $user->setPassword($hashedPassword);
    
        // Ustawianie roli
        $user->setRole($role);
    
        // Persistowanie użytkownika
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    
        return $user;
    }

    public function findUsernameById(int $id): ?string
    {
        return $this->createQueryBuilder('u')
            ->select('u.username')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
