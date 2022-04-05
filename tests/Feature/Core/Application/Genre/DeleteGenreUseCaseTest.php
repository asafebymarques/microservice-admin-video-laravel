<?php

namespace Tests\Feature\Core\Application\Genre;

use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Application\DTO\Genre\GenreInputDto;
use Core\Application\Genre\DeleteGenreUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DeleteGenreUseCaseTest extends TestCase
{
    public function testDelete()
    {
        $useCase = new DeleteGenreUseCase(
            new GenreEloquentRepository(new Model())
        );

        $genre = Model::factory()->create();

        $responseUseCase = $useCase->execute(new GenreInputDto(
            id: $genre->id
        ));

        $this->assertTrue($responseUseCase->success);

        $this->assertSoftDeleted('genres', [
            'id' => $genre->id
        ]);
    }
}
