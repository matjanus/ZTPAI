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
    // #[OA\Get(
    //     path: "/api/quiz/{id}",
    //     summary: "Get words for translation",
    //     parameters: [
    //         new OA\Parameter(
    //             name: "id",
    //             in: "path",
    //             required: true,
    //             description: "quiz ID",
    //             schema: new OA\Schema(type: "string")
    //         )
    //     ],
    //     responses: [
    //         new OA\Response(
    //             response: 200,
    //             description: "Quiz content",
    //             content: new OA\JsonContent(
    //                 type: "array",
    //                 items: new OA\Items(
    //                     type: "object",
    //                     properties: [
    //                         new OA\Property(property: "word", type: "string"),
    //                         new OA\Property(property: "translation", type: "string")
    //                     ]
    //                 ),
    //                 example: [
    //                     [ "word" => "kot", "translation" => "cat" ],
    //                     [ "word" => "krowa", "translation" => "cow" ]
    //                 ]
    //             )
    //         ),
    //         new OA\Response(
    //             response: 404,
    //             description: "Quiz not found",
    //             content: new OA\JsonContent(
    //                 type: "object",
    //                 properties: [
    //                     new OA\Property(property: "error", type: "string", example: "Quiz not found")
    //                 ]
    //             )
    //         )
    //     ]
    // )]
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
            'accsess' => $quiz->getAccess()
        ], $quizzes);

        return $this->json($data);
    }

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