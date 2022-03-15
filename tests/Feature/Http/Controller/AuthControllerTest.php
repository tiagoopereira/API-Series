<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
    }

    public function testUserShouldBeDeniedIfNotRegistered(): void
    {
        $payload = [
            'email' => 'teste@email.com',
            'password' => 'secret123'
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseStatus(401);
        $this->seeJsonStructure(['error', 'code', 'message']);
        $this->seeJsonEquals([
            'error' => true,
            'code' => 401,
            'message' => 'Wrong credentials'
        ]);
    }

    public function testUserShouldBeDeniedIfSendWrongPassword(): void
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'teste'
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseStatus(401);
        $this->seeJsonStructure(['error', 'code', 'message']);
        $this->seeJsonEquals([
            'error' => true,
            'code' => 401,
            'message' => 'Wrong credentials'
        ]);
    }

    public function testUserCanAuthenticate(): void
    {
        $user = User::factory()->create();

        $payload = [
            'email' => $user->email,
            'password' => 'secret123'
        ];

        $this->post(route('auth.login'), $payload);

        $this->assertResponseStatus(200);
        $this->seeJsonStructure(['access_token']);
    }
}
