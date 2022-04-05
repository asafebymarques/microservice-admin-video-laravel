<?php

namespace Tests\Feature\Core\Application\Genre;

use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Application\DTO\Genre\GenreInputDto;
use Core\Application\Genre\ListGenreUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListGenreUseCaseTest extends TestCase
{
    public function testFindById()
    {
        $useCase = new ListGenreUseCase(
            new GenreEloquentRepository(new Model())
        );

        $genre = Model::factory()->create();

        $responseUseCase = $useCase->execute(new GenreInputDto(
            id: $genre->id
        ));

        $this->assertEquals($genre->id, $responseUseCase->id);
        $this->assertEquals($genre->name, $responseUseCase->name);
    }
}
