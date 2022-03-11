<?php

use App\Models\Serie;
use Laravel\Lumen\Testing\DatabaseMigrations;

class SeriesControllerTest extends TestCase
{
    use DatabaseMigrations;

    const BASE_URI = '/api/series';
    public string $token;

    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed');

        $user = [
            'email' => 'teste@email.com',
            'password' => '123456'
        ];

        $response = $this->post('/api/auth/login', $user)->response;
        $this->token = 'Bearer ' . json_decode($response->getContent(), true)['access_token'];
    }

    public function testUserCantAccessSeriesWithoutLogIn(): void
    {
        $response = $this->post(self::BASE_URI)->response;

        $this->assertResponseStatus(401);
        $this->assertEquals('Unauthorized.', $response->getContent());
    }

    public function testUserCantCreateASerieWithNoName(): void
    {
        $this->post(self::BASE_URI, [], ['Authorization' => $this->token]);

        $this->assertResponseStatus(422);
        $this->seeJsonContains(['name' => ['The name field is required.']]);
    }

    public function testUserCanCreateASerie(): void
    {
        $this->post(self::BASE_URI, ['name' => 'How I Met Your Mother'], ['Authorization' => $this->token]);

        $this->assertResponseStatus(201);
        $this->seeInDatabase('series', ['name' => 'How I Met Your Mother']);
        $this->seeJsonContains([
            'id' => 1,
            'name' => 'How I Met Your Mother'
        ]);
        $this->seeJsonStructure([
            'id',
            'name',
            'links'
        ]);
    }

    public function testUserCanVisualizeSeries(): void
    {
        $this->get(self::BASE_URI, ['Authorization' => $this->token]);

        $this->assertResponseOk();
        $this->seeJsonContains(['data' => []]);
        $this->seeJsonStructure([
            'current_page',
            'data',
            'first_page_url',
            'from',
            'last_page',
            'last_page_url',
            'links',
            'next_page_url',
            'path',
            'per_page',
            'prev_page_url',
            'to',
            'total'
        ]);
    }

    public function testeUserCanVisualizeASerie(): void
    {
        $serie = Serie::create(['name' => 'How I Met Your Mother']);

        $this->get(self::BASE_URI . "/{$serie->id}", ['Authorization' => $this->token]);

        $this->assertResponseOk();
        $this->seeJsonContains([
            'id' => 1,
            'name' => 'How I Met Your Mother'
        ]);
        $this->seeJsonStructure([
            "id",
            "name",
            "links",
        ]);
    }

    public function testeUserCanUpdateASerie(): void
    {
        $serie = Serie::create(['name' => 'How I Met Your Mother']);
        $data = ['name' => 'How I Met Your Father'];

        $this->put(self::BASE_URI . "/{$serie->id}", $data, ['Authorization' => $this->token]);

        $this->assertResponseOk();
        $this->seeInDatabase('series', ['id' => $serie->id, 'name' => 'How I Met Your Father']);
        $this->seeJsonContains([
            'id' => 1,
            'name' => 'How I Met Your Father'
        ]);
        $this->seeJsonStructure([
            "id",
            "name",
            "links",
        ]);
    }

    public function testeUserCanDeleteASerie(): void
    {
        $serie = Serie::create(['name' => 'How I Met Your Mother']);

        $this->delete(self::BASE_URI . "/{$serie->id}", [], ['Authorization' => $this->token]);

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('series', ['id' => $serie->id]);
    }
}
