<?php

use App\Models\User;
use App\Models\Serie;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Illuminate\Contracts\Auth\Authenticatable;

class SeriesControllerTest extends TestCase
{
    use DatabaseMigrations;

    private Authenticatable $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testUserShouldNotCreateASerieWithoutRequiredFields(): void
    {
        $this->actingAs($this->user)->post(route('series.create'), []);

        $this->assertResponseStatus(422);
        $this->seeJsonContains(['name' => ['The name field is required.']]);
    }

    public function testUserCanCreateASerie(): void
    {
        $this->actingAs($this->user)->post(route('series.create'), ['name' => 'How I Met Your Mother']);

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
        $this->actingAs($this->user)->get(route('series.index'));

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

    public function testUserCanVisualizeASerie(): void
    {
        $serie = Serie::create(['name' => 'How I Met Your Mother']);

        $this->actingAs($this->user)->get(route('series.show', ['id' => $serie->id]));

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

    public function testUserCanUpdateASerie(): void
    {
        $serie = Serie::create(['name' => 'How I Met Your Mother']);
        $data = ['name' => 'How I Met Your Father'];

        $this->actingAs($this->user)->put(route('series.update', ['id' => $serie->id]), $data);

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

    public function testUserCanDeleteASerie(): void
    {
        $serie = Serie::create(['name' => 'How I Met Your Mother']);

        $this->actingAs($this->user)->delete(route('series.destroy', ['id' => $serie->id]), []);

        $this->assertResponseStatus(204);
        $this->notSeeInDatabase('series', ['id' => $serie->id]);
    }
}
