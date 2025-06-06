<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;

class QuizControllerTest extends WebTestCase
{
    private string $token;
    private KernelBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->client = static::createClient();

        $this->client->request('POST', '/api/login_check', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'username' => 'admin',
            'password' => 'admin'
        ]));

        $response = $this->client->getResponse();
        $data = json_decode($response->getContent(), true);

        if (!isset($data['token'])) {
            $this->fail('Token does not exist.');
        }

        $this->token = $data['token'];
    }

    private function authHeaders(): array
    {
        return [
            'HTTP_Authorization' => 'Bearer ' . $this->token,
            'CONTENT_TYPE' => 'application/json'
        ];
    }

    public function testGetQuizTitleSuccess(): void
    {
        $this->client->request('GET', '/api/quiz/1', [], [], $this->authHeaders());
        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Zwierzęta', $data['title']);
    }

    public function testGetQuizTitleNotFound(): void
    {
        $this->client->request('GET', '/api/quiz/999', [], [], $this->authHeaders());
        $this->assertResponseStatusCodeSame(404);
    }

    public function testGetQuizVocabularySuccess(): void
    {
        $this->client->request('GET', '/api/quiz/1/vocabulary', [], [], $this->authHeaders());
        $response = $this->client->getResponse();
        $this->assertResponseIsSuccessful();
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey(0, $data);
        $this->assertEquals('kot', $data[0]['word']);
    }

    public function testGetQuizVocabularyNotFound(): void
    {
        $this->client->request('GET', '/api/quiz/999/vocabulary', [], [], $this->authHeaders());
        $this->assertResponseStatusCodeSame(404);
    }

    public function testLastQuizzesSuccess(): void
    {
        $this->client->request('GET', '/api/user/last-quizzes', [], [], $this->authHeaders());
        $this->assertResponseIsSuccessful();
    }

    public function testLastQuizzesOrderingAfterPlaying(): void
    {
        // Play quiz 1
        $this->client->request('GET', '/api/quiz/1/vocabulary', [], [], $this->authHeaders());
        $this->assertResponseIsSuccessful();

        // Check that quiz 1 is first in recent quizzes
        $this->client->request('GET', '/api/user/last-quizzes', [], [], $this->authHeaders());
        $this->assertResponseIsSuccessful();

        $data = json_decode($this->client->getResponse()->getContent(), true);
        $this->assertNotEmpty($data);
        $this->assertEquals(1, $data[0]['id'], 'Quiz 1 should be the most recently played.');

    }

    public function testCreateQuizSuccess(): void
    {
        $payload = [
            'title' => 'Nowy Quiz',
            'access' => 'Public',
            'vocabulary' => [
                ['word' => 'kot', 'translation' => 'cat'],
                ['word' => 'pies', 'translation' => 'dog']
            ]
        ];

        $this->client->request('POST', '/api/create_quiz', [], [], $this->authHeaders(), json_encode($payload));
        $this->assertResponseStatusCodeSame(201);
    }

    public function testCreateQuizValidationError(): void
    {
        $this->client->request('POST', '/api/create_quiz', [], [], $this->authHeaders(), json_encode([]));
        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('error', $data);
    }

    public function testGetUserPublicQuizzesSuccess(): void
    {
        $this->client->request('GET', '/api/user/2/quizzes', [], [], $this->authHeaders());
        $this->assertResponseIsSuccessful();
    }

    public function testGetUserPublicQuizzesPagination(): void
    {
        $this->client->request('GET', '/api/user/2/quizzes?page=1', [], [], $this->authHeaders());
        $this->assertResponseIsSuccessful();
    }

    public function testGetMyQuizzesSuccess(): void
    {
        $this->client->request('GET', '/api/user/my-quizzes', [], [], $this->authHeaders());
        $this->assertResponseIsSuccessful();
    }

    public function testDeleteQuizUnauthorized(): void
    {
        $this->client->request('DELETE', '/api/quiz/3', [], [], $this->authHeaders());
        $this->assertResponseStatusCodeSame(403);
    }

    public function testDeleteQuizSuccess(): void
    {

        $payload = [
            'title' => 'Quiz do usunięcia',
            'access' => 'Public',
            'vocabulary' => [['word' => 'temp', 'translation' => 'temp']]
        ];

        $this->client->request('POST', '/api/create_quiz', [], [], $this->authHeaders(), json_encode($payload));
        $response = json_decode($this->client->getResponse()->getContent(), true);
        $quizId = $response['id'] ?? null;

        $this->assertNotNull($quizId);

        $this->client->request('DELETE', "/api/quiz/{$quizId}", [], [], $this->authHeaders());
        $this->assertResponseStatusCodeSame(204);
    }

    public function testCreateQuizInvalidAccess(): void
    {
        $payload = [
            'title' => 'Access not exist',
            'access' => 'private', //it should be Private
            'vocabulary' => [['word' => 'temp', 'translation' => 'temp']]
        ];

        $this->client->request('POST', '/api/create_quiz', [], [], $this->authHeaders(), json_encode($payload));
        $response = $this->client->getResponse();
        $this->assertResponseStatusCodeSame(400);
        $data = json_decode($response->getContent(), true);
        $this->assertEquals('Incorrect access type: private', $data['error']);
    }
}
