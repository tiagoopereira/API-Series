<?php

use App\Models\Episode;
use App\Models\Serie;
use Laravel\Lumen\Testing\DatabaseMigrations;

class EpisodesControllerTest extends TestCase
{
    use DatabaseMigrations;

    const BASE_URI = '/api/episodes';
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

    private function createSerie(): Serie
    {
        return Serie::create(['name' => 'How I Met Your Mother']);
    }

    public function testUserCantAccessEpisodesWithoutLogIn(): void
    {
        $response = $this->post(self::BASE_URI)->response;

        $this->assertResponseStatus(401);
        $this->assertEquals('Unauthorized.', $response->getContent());
    }

    public function testUserCantCreateAEpisode(): void
    {
        $this->post(self::BASE_URI, [], ['Authorization' => $this->token]);

        $this->assertResponseStatus(422);
        $this->seeJsonContains(['season' => ['The season field is required.']]);
        $this->seeJsonContains(['number' => ['The number field is required.']]);
        $this->seeJsonContains(['serie_id' => ['The serie id field is required.']]);
    }

    public function testUserCanCreateAEpisode(): void
    {
        $serie = $this->createSerie();

        $episode = [
            'season' => 1,
            'number' => 1,
            'watched' => false,
            'serie_id' => $serie->id
        ];

        $this->post(self::BASE_URI, $episode, ['Authorization' => $this->token]);

        $this->assertResponseStatus(201);
        $this->seeInDatabase('episodes', [
            'season' => 1,
            'number' => 1,
            'watched' => false,
            'serie_id' => $serie->id
        ]);

        $this->seeJsonContains([
            'season' => 1,
            'number' => 1,
            'watched' => false,
            'serie_id' => $serie->id
        ]);

        $this->seeJsonStructure([
            'id',
            'season',
            'number',
            'watched',
            'serie_id',
            'links'
        ]);
    }

    public function testUserCanVisualizeEpisodes(): void
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

    public function testeUserCanVisualizeAEpisode(): void
    {
        $serie = $this->createSerie();
        $episode = Episode::create([
            'season' => 1,
            'number' => 1,
            'serie_id' => $serie->id
        ]);

        $this->get(self::BASE_URI . "/{$episode->id}", ['Authorization' => $this->token]);

        $this->assertResponseOk();
        $this->seeJsonContains([
            'season' => 1,
            'number' => 1,
            'watched' => false,
            'serie_id' => $serie->id
        ]);

        $this->seeJsonStructure([
            'id',
            'season',
            'number',
            'watched',
            'serie_id',
            'links'
        ]);
    }

    public function testeUserCanUpdateEpisode(): void
    {
        $serie = $this->createSerie();
        $episode = Episode::create([
            'season' => 1,
            'number' => 1,
            'watched' => false,
            'serie_id' => $serie->id
        ]);

        $data = [
            'season' => 2,
            'number' => 3,
            'watched' => true,
            'serie_id' => $serie->id
        ];

        $this->put(self::BASE_URI . "/{$episode->id}", $data, ['Authorization' => $this->token]);

        $this->assertResponseOk();
        $this->seeInDatabase('episodes', [
            'id' => $episode->id,
            'season' => 2,
            'number' => 3,
            'watched' => true,
            'serie_id' => $serie->id
        ]);

        $this->seeJsonContains([
            'season' => 2,
            'number' => 3,
            'watched' => true,
            'serie_id' => $serie->id
        ]);

        $this->seeJsonStructure([
            'id',
            'season',
            'number',
            'watched',
            'serie_id',
            'links'
        ]);
    }

    public function testeUserCanDeleteAEpisode(): void
    {
        $serie = $this->createSerie();
        $episode = Episode::create([
            'season' => 1,
            'number' => 1,
            'watched' => false,
            'serie_id' => $serie->id
        ]);

        $this->delete(self::BASE_URI . "/{$episode->id}", [], ['Authorization' => $this->token]);

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('episodes', ['id' => $episode->id]);
    }

    public function testeUserCanVisualizeSerieEpisodes(): void
    {
        $serie = Serie::create(['name' => 'How I Met Your Mother']);
        Episode::create(['season' => 1, 'number' => 1, 'watched' => false, 'serie_id' => $serie->id]);

        $this->get("api/series/{$serie->id}/episodes", ['Authorization' => $this->token]);

        $this->assertResponseOk();
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
}
