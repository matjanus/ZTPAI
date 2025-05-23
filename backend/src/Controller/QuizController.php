<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use OpenApi\Attributes as OA;


class QuizController extends AbstractController
{   
    #[OA\Get(
        path: "/api/quiz/{id}",
        summary: "Get words for translation",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "quiz ID",
                schema: new OA\Schema(type: "string")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Quiz content",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        type: "object",
                        properties: [
                            new OA\Property(property: "word", type: "string"),
                            new OA\Property(property: "translation", type: "string")
                        ]
                    ),
                    example: [
                        [ "word" => "kot", "translation" => "cat" ],
                        [ "word" => "krowa", "translation" => "cow" ]
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Quiz not found",
                content: new OA\JsonContent(
                    type: "object",
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Quiz not found")
                    ]
                )
            )
        ]
    )]
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
    public function getLastPlayedQuizzes(LoggerInterface $logger): JsonResponse
    {
        $logger->info('To jest testowy wpis loga');
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