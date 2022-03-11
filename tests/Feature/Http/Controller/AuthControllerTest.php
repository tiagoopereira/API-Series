<?php

use App\Models\User;
use Laravel\Lumen\Testing\DatabaseMigrations;

class AuthControllerTest extends TestCase
{
    use DatabaseMigrations;

    const BASE_URI = '/api/auth/login';

    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed');
    }

    public function testUserCantLogin(): void
    {
        $user = User::factory()->create();
        $userArr = [
            'email' => $user->email,
            'password' => $user->password
        ];

        $this->post(self::BASE_URI, $userArr);

        $this->assertResponseStatus(401);
        $this->seeJsonStructure(['error', 'code', 'message']);
        $this->seeJsonEquals([
            'error' => true,
            'code' => 401,
            'message' => 'Email or password are invalid'
        ]);
    }

    public function testUserCanLogin(): void
    {
        $user = [
            'email' => 'teste@email.com',
            'password' => '123456'
        ];

        $this->post(self::BASE_URI, $user);

        $this->assertResponseStatus(200);
        $this->seeJsonStructure(['access_token']);
    }
}
