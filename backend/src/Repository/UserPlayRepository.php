<?php

namespace App\Repository;

use App\Entity\UserPlay;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;

/**
 * @extends ServiceEntityRepository<UserPlay>
 */
class UserPlayRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPlay::class);
    }

    //    /**
    //     * @return UserPlay[] Returns an array of UserPlay objects
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

    //    public function findOneBySomeField($value): ?UserPlay
    //    {
    //        return $this->createQueryBuilder('u')
    //            ->andWhere('u.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findLastPlayedByUserPaginated(User $user, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('up')
            ->join('up.quiz', 'q')
            ->join('q.access', 'a')
            ->join('q.owner', 'o')
            ->select('q.id AS id', 'q.quizName AS quizName', 'o.username AS owner', 'o.id AS ownerId', 'MAX(up.date) AS lastPlayed')
            ->where('up.player = :user')
            ->andWhere('q.owner = :user OR a.accessName != :private')
            ->setParameter('user', $user)
            ->setParameter('private', 'Private')
            ->groupBy('q.id, q.quizName, o.username, o.id')
            ->orderBy('lastPlayed', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getArrayResult();
    }
}
