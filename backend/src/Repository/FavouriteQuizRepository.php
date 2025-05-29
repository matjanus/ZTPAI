<?php

namespace App\Repository;

use App\Entity\FavouriteQuiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Entity\Quiz;

/**
 * @extends ServiceEntityRepository<FavouriteQuiz>
 */
class FavouriteQuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavouriteQuiz::class);
    }

//    /**
//     * @return FavouriteQuiz[] Returns an array of FavouriteQuiz objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FavouriteQuiz
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
        public function findFavouritesByUserPaginated(User $user, int $page = 1, int $limit = 10): array
    {
        return $this->createQueryBuilder('f')
            ->join('f.quiz', 'q')
            ->addSelect('q')
            ->where('f.patron = :user')
            ->setParameter('user', $user)
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findOneByUserAndQuiz(User $user, Quiz $quiz): ?FavouriteQuiz
    {
        return $this->findOneBy(['patron' => $user, 'quiz' => $quiz]);
    }

}
