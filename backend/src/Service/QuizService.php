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
}