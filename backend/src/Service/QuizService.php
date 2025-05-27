<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\QuizVocabulary;
use App\Entity\Quiz;
use App\Repository\UserPlayRepository;
use App\Repository\AccessRepository;
use Doctrine\ORM\EntityManagerInterface;


class QuizService
{
    private UserPlayRepository $userPlayRepository;

    public function __construct(
        UserPlayRepository $userPlayRepository,
        private EntityManagerInterface $em,
        private AccessRepository $accessRepository)
    {
        $this->userPlayRepository = $userPlayRepository;
    }

    public function getLastPlayedQuizzesPaginated(User $user, int $page = 1, int $limit = 10): array
    {
        $plays = $this->userPlayRepository->findLastPlayedByUserPaginated($user, $page, $limit);
        return array_map(fn($play) => $play->getQuiz(), $plays);
    }

    public function createQuiz(string $title, string $accessName, array $vocabulary, User $user): Quiz
    {
        if (empty($title)) {
            throw new \InvalidArgumentException("Tytuł quizu nie może być pusty.");
        }

        if (empty($vocabulary)) {
            throw new \InvalidArgumentException("Quiz musi zawierać przynajmniej jedno słowo.");
        }

        $access = $this->accessRepository->findOneBy(['accessName' => $accessName]);
        if (!$access) {
            throw new \InvalidArgumentException("Nieprawidłowy typ dostępu: $accessName");
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
}