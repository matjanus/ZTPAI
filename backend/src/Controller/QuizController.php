<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;
use OpenApi\Attributes as OA;
use App\Service\QuizService;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


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


    #[Route('/api/user/last-quizzes', name: 'user_last_quizzes', methods: ['GET'])]
    public function lastQuizzes(
        #[CurrentUser] User $user,
        QuizService $quizService,
        Request $request
    ): JsonResponse {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10;

        $quizzes = $quizService->getLastPlayedQuizzesPaginated($user, $page, $limit);

        return $this->json($quizzes, 200);
    }

    #[Route('/api/create_quiz', name: 'create_quiz', methods: ['POST'])]
    public function create(
        Request $request,
        QuizService $quizService,
        #[CurrentUser] User $user
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $title = $data['title'] ?? '';
        $accessName = $data['access'] ?? '';
        $vocabulary = $data['vocabulary'] ?? [];

        try {
            $quiz = $quizService->createQuiz($title, $accessName, $vocabulary, $user);

            return $this->json([
                'message' => 'Quiz created successfully.',
                'title' => $quiz->getQuizName()
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    #[Route('/api/user/{id}/quizzes', name: 'get_user_quizzes', methods: ['GET'])]
    public function getUserPublicQuizzes(
        int $id,
        QuizService $quizService,
        Request $request
    ): JsonResponse {
        $page = max(1, (int)$request->query->get('page', 1));
        $limit = 10;

        $quizzes = $quizService->getPublicQuizzesByUser($id, $page, $limit);

        $data = array_map(fn($quiz) => [
            'id' => $quiz->getId(),
            'quizName' => $quiz->getQuizName(),
        ], $quizzes);

        return $this->json($data, 200);
    }
}