<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use OpenApi\Attributes as OA;
use App\Service\QuizService;
use App\Entity\User;
use Symfony\Component\Security\Http\Attribute\CurrentUser;


class QuizController extends AbstractController
{   

    #[OA\Get(
        path: "/api/quiz/{id}",
        summary: "Get quiz title by ID",
        security: [ ['bearerAuth' => []] ], 
        tags: ["Quiz"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Quiz title returned",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "title", type: "string", example: "Animals Vocabulary")
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: "Access denied",
                content: new OA\JsonContent(properties: [new OA\Property(property: "error", type: "string")])
            ),
            new OA\Response(
                response: 404,
                description: "Quiz not found",
                content: new OA\JsonContent(properties: [new OA\Property(property: "error", type: "string")])
            )
        ]
    )]
    #[Route('api/quiz/{id}', name: 'get_quiz_title', methods: ['GET'])]
    public function getQuizTitle(
        int $id,
        #[CurrentUser] User $user,
        QuizService $quizService
    ): JsonResponse {
        try {
            $title = $quizService->getQuizTitle($id, $user);
            return new JsonResponse(['title' => $title], 200);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 403);
        }
    }


    #[OA\Get(
        path: "/api/quiz/{id}/vocabulary",
        summary: "Get vocabulary of a quiz",
        security: [ ['bearerAuth' => []] ], 
        tags: ["Quiz"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Vocabulary data returned",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            ),
            new OA\Response(
                response: 403,
                description: "Access denied",
                content: new OA\JsonContent(properties: [new OA\Property(property: "error", type: "string")])
            ),
            new OA\Response(
                response: 404,
                description: "Quiz not found",
                content: new OA\JsonContent(properties: [new OA\Property(property: "error", type: "string")])
            )
        ]
    )]
    #[Route('api/quiz/{id}/vocabulary', name: 'get_quiz_vocabulary', methods: ['GET'])]
    public function getQuizVocabulary(
        int $id,
        #[CurrentUser] User $user,
        QuizService $quizService
    ): JsonResponse {
        try {
            $data = $quizService->getQuizVocabulary($id, $user);
            return new JsonResponse($data, 200);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        } catch (AccessDeniedHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 403);
        }
    }




    #[OA\Get(
        path: "/api/user/last-quizzes",
        summary: "Get last played quizzes of the current user",
        security: [ ['bearerAuth' => []] ], 
        tags: ["Quiz"],
        parameters: [
            new OA\Parameter(name: "page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of last played quizzes",
                content: new OA\JsonContent(type: "array", items: new OA\Items(type: "object"))
            )
        ]
    )]
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


    #[OA\Post(
        path: "/api/create_quiz",
        summary: "Create a new quiz",
        security: [ ['bearerAuth' => []] ], 
        tags: ["Quiz"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "title", type: "string", example: "Food Vocabulary"),
                    new OA\Property(property: "access", type: "string", example: "private"),
                    new OA\Property(property: "vocabulary", type: "array", items: new OA\Items(type: "object"))
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Quiz created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Quiz created successfully."),
                        new OA\Property(property: "title", type: "string", example: "Food Vocabulary"),
                        new OA\Property(property: "id", type: "int", example: "11")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validation error",
                content: new OA\JsonContent(
                    oneOf: [
                        new OA\Schema(
                            properties: [new OA\Property(property: "error", type: "string", example: "Title is required.")]
                        ),
                        new OA\Schema(
                            properties: [new OA\Property(property: "error", type: "string", example: "Access type invalid.")]
                        )
                    ]
                )
            )
        ]
    )]
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
                'title' => $quiz->getQuizName(),
                'id' => $quiz->getId()
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return $this->json([
                'error' => $e->getMessage()
            ], 400);
        }
    }

    #[OA\Get(
            path: "/api/user/{id}/quizzes",
            summary: "Get public quizzes by user ID",
            security: [ ['bearerAuth' => []] ], 
            tags: ["Quiz"],
            parameters: [
                new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer")),
                new OA\Parameter(name: "page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 1))
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: "List of public quizzes",
                    content: new OA\JsonContent(type: "array", items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer"),
                            new OA\Property(property: "quizName", type: "string")
                        ]
                    ))
                )
            ]
        )]
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

    #[OA\Get(
        path: "/api/user/my-quizzes",
        summary: "Get quizzes created by current user",
        security: [ ['bearerAuth' => []] ], 
        tags: ["Quiz"],
        parameters: [
            new OA\Parameter(name: "page", in: "query", required: false, schema: new OA\Schema(type: "integer", default: 1))
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of user’s quizzes",
                content: new OA\JsonContent(type: "array", items: new OA\Items(
                    properties: [
                        new OA\Property(property: "id", type: "integer"),
                        new OA\Property(property: "quizName", type: "string"),
                        new OA\Property(property: "access", type: "string")
                    ]
                ))
            )
        ]
    )]
    #[Route('/api/user/my-quizzes', name: 'user_my_quizzes', methods: ['GET'])]
    public function getMyQuizzes(
        #[CurrentUser] User $user,
        QuizService $quizService,
        Request $request
    ): JsonResponse {
        $page = max(1, (int) $request->query->get('page', 1));
        $limit = 10;

        $quizzes = $quizService->getUserQuizzes($user, $page, $limit);

        $data = array_map(fn($quiz) => [
            'id' => $quiz->getId(),
            'quizName' => $quiz->getQuizName(),
            'access' => $quiz->getAccess()
        ], $quizzes);

        return $this->json($data);
    }

    #[OA\Delete(
        path: "/api/quiz/{id}",
        summary: "Delete a quiz by ID",
        security: [ ['bearerAuth' => []] ], 
        tags: ["Quiz"],
        parameters: [
            new OA\Parameter(name: "id", in: "path", required: true, schema: new OA\Schema(type: "integer"))
        ],
        responses: [
            new OA\Response(
                response: 204,
                description: "Quiz deleted"
            ),
            new OA\Response(
                response: 400,
                description: "Invalid operation",
                content: new OA\JsonContent(
                    properties: [new OA\Property(property: "error", type: "string", example: "You do not have permission.")]
                )
            )
        ]
    )]
    #[Route('/api/quiz/{id}', name: 'delete_quiz', methods: ['DELETE'])]
    public function deleteQuiz(
        int $id,
        #[CurrentUser] User $user,
        QuizService $quizService
    ): JsonResponse {
        try {
            $quizService->deleteQuiz($id, $user);
            return new JsonResponse(null, 204);
        } catch (\RuntimeException $e) {
            return $this->json(['error' => $e->getMessage()], $e->getCode() ?: 400);
        }
    }
}