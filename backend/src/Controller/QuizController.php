<?php
namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuizController extends AbstractController
{
    #[Route('/api/quiz/{id}', name: 'get_quiz_vocabulary', methods: ['GET'])]
    public function getQuizVocabulary(int $id): JsonResponse
    {
    $quizzes = [
        1 => [
            'words' => [['cat', 'kot'], ['dog', 'pies'], ['cow', 'krowa']],
            'quiz_name'  => 'Zwierzeta',
            'owner_id' => '1'
            ],
        2 => [
            'words' => [['room', 'pokój'], ['kitchen', 'kuchnia'], ['bathroom', 'łazienka']],
            'quiz_name'  => 'dom',
            'owner_id' => '2'
            ],
        ];
    if (!isset($quizzes[$id])) {
        return $this->json(['error' => 'Quiz not found'], 404);
    }
    return $this->json($quizzes[$id]);
    }
}