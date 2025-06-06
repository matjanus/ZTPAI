<?php
// tests/Controller/UserControllerTest.php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    private $client;
    private $token;

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

    public function testGetMe(): void
    {
        $this->client->request('GET', '/api/me', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $response = json_decode($this->client->getResponse()->getContent(), true);

        $this->assertResponseIsSuccessful();
        $this->assertEquals(1, $response['id']);
    }

    public function testGetUserById(): void
    {
        $this->client->request('GET', '/api/user/' . '2', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseIsSuccessful();
    }

    public function testDeleteUser(): void
    {
        $this->client->request('DELETE', '/api/user/delete', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseStatusCodeSame(204);
    }
}
