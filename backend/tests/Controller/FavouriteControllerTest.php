<?php

namespace Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FavouriteControllerTest extends WebTestCase
{
    private string $token;
    private AbstractBrowser $client;

    protected function setUp(): void
    {
        self::ensureKernelShutdown();
        $this->$client = static::createClient();

        $this->$client->request('POST', '/api/login_check', [], [], [
            'CONTENT_TYPE' => 'application/json'
        ], json_encode([
            'username' => 'admin',
            'password' => 'admin'
        ]));

        $response = $this->$client->getResponse();
        $data = json_decode($response->getContent(), true);
        

        if (!isset($data['token'])) {
            $this->fail('Token does not exist.');
        }

        $this->token = $data['token'];
    }

    public function testGetFavourites(): void
    {
        $this->$client->request('GET', '/api/favourites', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');
    }

    public function testAddFavouriteSuccess(): void
    {
        

        $this->$client->request('POST', '/api/favourites/6', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseStatusCodeSame(201);

        $this->$client->request('DELETE', '/api/favourites/6', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseStatusCodeSame(204);
    }

    public function testAddFavouriteAlreadyAdded(): void
    {
        
        $this->$client->request('POST', '/api/favourites/1', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertStringContainsString('already', $this->$client->getResponse()->getContent());
    }

    public function testAddFavouriteNonExistentQuiz(): void
    {
        

        $this->$client->request('POST', '/api/favourites/999', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        $this->assertStringContainsString('not found', $this->$client->getResponse()->getContent());
    }

    public function testRemoveFavouriteNotExists(): void
    {
        

        // Quiz 999 does not exist
        $this->$client->request('DELETE', '/api/favourites/999', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
    }

    public function testCheckFavouriteTrue(): void
    {
        

        $this->$client->request('GET', '/api/favourites/1/check', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->$client->getResponse()->getContent(), true);
        $this->assertTrue($data['favourite']);
    }

    public function testCheckFavouriteFalse(): void
    {
        

        $this->$client->request('GET', '/api/favourites/6/check', [], [], [
            'HTTP_Authorization' => 'Bearer ' . $this->token
        ]);

        $this->assertResponseIsSuccessful();
        $data = json_decode($this->$client->getResponse()->getContent(), true);
        $this->assertFalse($data['favourite']);
    }

    public function testAccessWithoutToken(): void
    {
        

        $this->$client->request('GET', '/api/favourites');

        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
}
