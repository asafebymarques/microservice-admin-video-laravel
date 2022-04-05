<?php

namespace Tests\Feature\Core\Application\Genre;

use App\Models\Genre as Model;
use App\Repositories\Eloquent\GenreEloquentRepository;
use Core\Application\DTO\Genre\List\ListGenresInputDto;
use Core\Application\Genre\ListGenresUseCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ListGenresUseCaseTest extends TestCase
{
    public function testFindAll()
    {
        $useCase = new ListGenresUseCase(
            new GenreEloquentRepository(new Model())
        );

        $genre = Model::factory()->count(100)->create();

        $responseUseCase = $useCase->execute(new ListGenresInputDto());

        $this->assertEquals(15, count($responseUseCase->items));
        $this->assertEquals(100, $responseUseCase->total);
    }
}
