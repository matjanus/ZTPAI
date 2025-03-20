<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuizController extends AbstractController
{
    #[Route('/api/quiz/{id}', name: 'get_quiz_vocabulary', methods: ['GET'])]
    public function getQuizVocabulary(string $id): JsonResponse
    {
        $quizzes = [
            'a24n74dfdfjgh3ds' => [
                ['word' => 'kot', "translation"  => 'cat'],
                ['word' => 'krowa', "translation"  => 'cow'],
                ['word' => 'pies', "translation"  => 'dog']
                ],
            'a24n74dfdfjgh3ds' => [
                ['word' => 'pokój', "translation"  => 'room'],
                ['word' => 'kuchnia', "translation"  => 'kitchen'],
                ['word' => 'łazienka', "translation"  => 'bathroom']
                ],
            ];

        if (!isset($quizzes[$id])) {
            return $this->json(['error' => 'Quiz not found'], 404);
        }
        return $this->json($quizzes[$id]);
        }

        #[Route('/api/users_quizzes/{id}', name: 'get_users_quizzes', methods: ['GET'])]
        public function getUsersQuizzes(int $id): JsonResponse
        {
        $quizzes = [
            [
                'quiz_name'  => 'Zwierzeta',
                'quiz_id'=> "124574dfdasghhds",
                'owner_id' => 1,
                'access'=> 3
            ],
            [
                'quiz_name'  => 'dom',
                'quiz_id'=> "a24n74dfdfjgh3ds",
                'owner_id' => 2,
                'access'=> 3
            ]
        ];

        $result = array_filter($quizzes, function($quiz) use ($id) {
            return $quiz["owner_id"] === $id;
        });

        if (empty($result)) {
            return $this->json(['error' => 'No quiz found'], 404);
        }
        return $this->json($result);
    }

    #[Route('/api/last_played', name: 'get_last_played_quizzes', methods: ['GET'])]
    public function getLastPlayedQuizzes(): JsonResponse
    {
        $quizzes = [
            [
                'quiz_name'  => 'Zwierzeta',
                'quiz_id'=> "124574dfdasghhds",
                'owner_id' => 1,
                'access'=> 3
            ],
            [
                'quiz_name'  => 'dom',
                'quiz_id'=> "a24n74dfdfjgh3ds",
                'owner_id' => 2,
                'access'=> 3
            ]
            ];

        if (empty($quizzes)) {
            return $this->json(['error' => 'No quiz found'], 404);
        }
        return $this->json($quizzes);
    }
}