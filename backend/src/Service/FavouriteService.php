<?php
namespace App\Service;

use App\Entity\FavouriteQuiz;
use App\Entity\User;
use App\Entity\Quiz;
use App\Repository\FavouriteQuizRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class FavouriteService
{
    public function __construct(
        private FavouriteQuizRepository $favouriteRepo,
        private EntityManagerInterface $em,
        private QuizRepository $quizRepo
    ) {}

    public function addToFavourites(User $user, int $quizId): void
    {
        $quiz = $this->quizRepo->find($quizId);
        if (!$quiz) {
            throw new NotFoundHttpException("Quiz not found");
        }

        if ($this->favouriteRepo->findOneByUserAndQuiz($user, $quiz)) {
            throw new BadRequestHttpException("Quiz already in favourites");
        }

        $fav = new FavouriteQuiz();
        $fav->setPatron($user);
        $fav->setQuiz($quiz);

        $this->em->persist($fav);
        $this->em->flush();
    }

    public function removeFromFavourites(User $user, int $quizId): void
    {
        $quiz = $this->quizRepo->find($quizId);
        if (!$quiz) {
            throw new NotFoundHttpException("Quiz not found");
        }

        $fav = $this->favouriteRepo->findOneByUserAndQuiz($user, $quiz);
        if (!$fav) {
            throw new BadRequestHttpException("This quiz is not in favourites");
        }

        $this->em->remove($fav);
        $this->em->flush();
    }

    public function getFavourites(User $user, int $page, int $limit): array
    {
        return $this->favouriteRepo->findFavouritesByUserPaginated($user, $page, $limit);
    }

    public function isFavourite(User $user, int $quizId): bool
    {
        $quiz = $this->quizRepo->find($quizId);
        if (!$quiz) {
            throw new NotFoundHttpException("Quiz not found");
        }

        return $this->favouriteRepo->findOneByUserAndQuiz($user, $quiz) !== null;
    }
}
