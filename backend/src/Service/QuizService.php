<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\QuizVocabulary;
use App\Entity\Quiz;
use App\Repository\UserPlayRepository;
use App\Repository\AccessRepository;
use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;



class QuizService
{
    private UserPlayRepository $userPlayRepository;

    public function __construct(
        UserPlayRepository $userPlayRepository,
        private EntityManagerInterface $em,
        private AccessRepository $accessRepository,
        private QuizRepository $quizRepository
        )
    {
        $this->userPlayRepository = $userPlayRepository;
    }

    public function getLastPlayedQuizzesPaginated(User $user, int $page, int $limit): array
    {
        return $this->userPlayRepository->findLastPlayedByUserPaginated($user, $page, $limit);
    }

    public function createQuiz(string $title, string $accessName, array $vocabulary, User $user): Quiz
    {
        if (empty($title)) {
            throw new \InvalidArgumentException("The title of the quiz must not be empty.");
        }

        if (empty($vocabulary)) {
            throw new \InvalidArgumentException("The quiz must contain at least one word.");
        }

        $access = $this->accessRepository->findOneBy(['accessName' => $accessName]);
        if (!$access) {
            throw new \InvalidArgumentException("Incorrect access type: $accessName");
        }

        $quiz = new Quiz();
        $quiz->setQuizName($title);
        $quiz->setOwner($user);
        $quiz->setAccess($access);

        $this->em->persist($quiz);

        foreach ($vocabulary as $entry) {
            if (empty($entry['word']) || empty($entry['translation'])) {
                continue;
            }

            $word = new QuizVocabulary();
            $word->setQuiz($quiz);
            $word->setWord($entry['word']);
            $word->setTranslation($entry['translation']);

            $this->em->persist($word);
        }

        $this->em->flush();

        return $quiz;
    }


    public function getPublicQuizzesByUser(int $userId, int $page = 1, int $limit = 10): array
    {
        return $this->quizRepository->findPublicQuizzesByUserPaginated($userId, $page, $limit);
    }

    public function getUserQuizzes(User $user, int $page = 1, int $limit = 10): array
    {
        return $this->quizRepository->findOwnerQuizzesPaginated($user, $page, $limit);
    }

    public function deleteQuiz(int $quizId, User $user): void
    {
        $quiz = $this->quizRepository->findOneById($quizId);

        if (!$quiz) {
            throw new \RuntimeException('Quiz not found', 404);
        }

        if ($quiz->getOwner()->getId() !== $user->getId()) {
            throw new \RuntimeException('Access denied. You do not own this quiz.', 403);
        }

        $this->em->remove($quiz);
        $this->em->flush();
    }


    public function getQuizTitle(int $quizId, User $currentUser): string
    {
        $res = $this->quizRepository->findById($quizId);

        if (!$res) {
            throw new NotFoundHttpException("Quiz not found.");
        }

        $quiz = $res[0];

        if (
            $quiz->getAccess()->getAccessName() === 'Private' &&
            $quiz->getOwner() !== $currentUser
        ) {
            throw new AccessDeniedHttpException("You do not have access to this quiz.");
        }

        return $quiz->getQuizName();
    }

    public function getQuizVocabulary(int $quizId, User $currentUser): array
    {
        $res = $this->quizRepository->findById($quizId);

        if (!$res) {
            throw new NotFoundHttpException("Quiz not found.");
        }

        $quiz = $res[0];

        if (
            $quiz->getAccess()->getAccessName() === 'Private' &&
            $quiz->getOwner() !== $currentUser
        ) {
            throw new AccessDeniedHttpException("You do not have access to this quiz.");
        }

        $vocabularies = $quiz->getQuizVocabularies();

        return array_map(function ($v) {
            return [
                'word' => $v->getWord(),
                'translation' => $v->getTranslation(),
            ];
        }, $vocabularies->toArray());
    }

}   