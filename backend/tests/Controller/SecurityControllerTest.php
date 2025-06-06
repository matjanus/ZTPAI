<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{   

        public function testRegisterAndDeleteUser(): void
    {
        $email = $this->generateRandomAlphanumericString().'@t.t';
        $username = $this->generateRandomAlphanumericString().'aA';
        $password = 'StrongP@ss123';
        $client = static::createClient();

        // Register
        $client->request('POST', '/api/register', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'username' => $username,
            'password' => $password,
            'email' => $email
        ]));

        $response = $client->getResponse();
        $data = json_decode($response->getContent(), true);

        $this->assertResponseStatusCodeSame(201);
        $this->assertArrayHasKey('id', $data);

        $serId = $data['id'];

        // Login and get token
        $client->request('POST', '/api/login_check', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => $data['username'],
            'password' => $password
        ]));

        $loginData = json_decode($client->getResponse()->getContent(), true);
        $this->token = $loginData['token'] ?? null;

        $this->assertNotNull($this->token);

        // Delete
        $client->request('DELETE', '/api/user/delete', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseStatusCodeSame(204);
    }

    public function testLoginSuccessAndReceiveTokens(): void
    {
        $client = static::createClient();

        $client->request('POST', '/api/login_check', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'username' => 'strong',
            'password' => 'admin@admin.COM'
        ]));

        $this->assertResponseIsSuccessful();
        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertArrayHasKey('token', $data);
        $this->assertArrayHasKey('refresh_token', $data);
    }

    public function testChangePasswordWithCorrectOldPassword(): void
    {
        $client = static::createClient();

        // Login first
        $client->request('POST', '/api/login_check', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'username' => 'strong',
            'password' => 'admin@admin.COM'
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);
        $token = $data['token'] ?? null;
        $this->assertNotNull($token);

        // Attempt to change password to the same value (should succeed)
        $client->request('POST', '/api/user/change-password', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'oldPassword' => 'admin@admin.COM',
            'newPassword' => 'admin@admin.COM',
        ]));

        $this->assertResponseStatusCodeSame(204);
    }

    public function testChangePasswordWithIncorrectOldPassword(): void
    {
        $client = static::createClient();

        // Login first
        $client->request('POST', '/api/login_check', [], [], [
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'username' => 'strong',
            'password' => 'admin@admin.COM'
        ]));

        $data = json_decode($client->getResponse()->getContent(), true);
        $token = $data['token'] ?? null;
        $this->assertNotNull($token);

        // Attempt to change password with incorrect old password
        $client->request('POST', '/api/user/change-password', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $token,
            'CONTENT_TYPE' => 'application/json',
        ], json_encode([
            'oldPassword' => 'admin@admin.CO',
            'newPassword' => 'admin@admin.COM',
        ]));

        $this->assertResponseStatusCodeSame(400);
        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('Could not change the password', $responseData['error'] ?? '');
    }

    private function generateRandomAlphanumericString(int $length = 15): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}