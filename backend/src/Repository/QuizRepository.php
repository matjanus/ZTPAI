<?php

namespace App\Repository;

use App\Entity\Quiz;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    //    /**
    //     * @return Quiz[] Returns an array of Quiz objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('q.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Quiz
    //    {
    //        return $this->createQueryBuilder('q')
    //            ->andWhere('q.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findPublicQuizzesByUserPaginated(int $userId, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('q')
            ->join('q.owner', 'o')
            ->join('q.access', 'a')
            ->where('o.id = :userId')
            ->andWhere('a.accessName = :public')
            ->setParameter('userId', $userId)
            ->setParameter('public', 'Public')
            ->orderBy('q.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findOwnerQuizzesPaginated(User $user, int $page = 1, int $limit = 10): array
    {
        $offset = ($page - 1) * $limit;

        return $this->createQueryBuilder('q')
            ->where('q.owner = :owner')
            ->setParameter('owner', $user)
            ->orderBy('q.id', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findOneById(int $id): ?Quiz
{
    return $this->createQueryBuilder('q')
        ->where('q.id = :id')
        ->setParameter('id', $id)
        ->getQuery()
        ->getOneOrNullResult();
}
}
