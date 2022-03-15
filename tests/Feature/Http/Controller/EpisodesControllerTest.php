<?php

use App\Models\User;
use App\Models\Serie;
use App\Models\Episode;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Contracts\Auth\Authenticatable;

class EpisodesControllerTest extends TestCase
{
    use DatabaseMigrations;

    private Authenticatable $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    private function createSerie(): Serie
    {
        return Serie::create(['name' => 'How I Met Your Mother']);
    }

    public function testShouldNotCreateAEpisodeWithoutRequiredFields(): void
    {
        $this->actingAs($this->user)->post(route('episodes.create'), []);

        $this->assertResponseStatus(422);
        $this->seeJsonContains(['season' => ['The season field is required.']]);
        $this->seeJsonContains(['number' => ['The number field is required.']]);
        $this->seeJsonContains(['serie_id' => ['The serie id field is required.']]);
    }

    public function testCanCreateAEpisode(): void
    {
        $serie = $this->createSerie();

        $episode = [
            'season' => 1,
            'number' => 1,
            'watched' => false,
            'serie_id' => $serie->id
        ];

        $this->actingAs($this->user)->post(route('episodes.create'), $episode);

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
        $this->actingAs($this->user)->get(route('episodes.index'));

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

        $this->actingAs($this->user)->get(route('episodes.show', ['id' => $episode->id]));

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

        $this->actingAs($this->user)->put(route('episodes.update', ['id' => $episode->id]), $data);

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

        $this->actingAs($this->user)->delete(route('episodes.destroy', ['id' => $episode->id]), []);

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('episodes', ['id' => $episode->id]);
    }

    public function testeUserCanVisualizeSerieEpisodes(): void
    {
        $serie = Serie::create(['name' => 'How I Met Your Mother']);
        Episode::create(['season' => 1, 'number' => 1, 'watched' => false, 'serie_id' => $serie->id]);

        $this->actingAs($this->user)->get(route('series.episodes', ['serieId' => $serie->id]));

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
