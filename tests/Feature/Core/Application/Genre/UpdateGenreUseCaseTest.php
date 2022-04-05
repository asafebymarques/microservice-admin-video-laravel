<?php

namespace Tests\Feature\Core\Application\Genre;

use App\Models\Genre as Model;
use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\{
    CategoryEloquentRepository,
    GenreEloquentRepository
};
use App\Repositories\Transaction\DBTransaction;
use Core\Application\DTO\Genre\Update\GenreUpdateInputDto;
use Core\Application\Genre\UpdateGenreUseCase;
use Core\Domain\Exception\NotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateGenreUseCaseTest extends TestCase
{

    public function testUpdate()
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $genreDb = Model::factory()->create();

        $categories = ModelCategory::factory()->count(15)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $useCase->execute(
            new GenreUpdateInputDto(
                id: $genreDb->id,
                name: 'Name Updated',
                categoriesId: $categoriesIds
            ),
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'Name Updated'
        ]);

        $this->assertDatabaseCount('category_genre', 15);
    }

    public function test_exception_insert_genre_with_categories_ids_invalid()
    {
        $this->expectException(NotFoundException::class);

        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $genreDb = Model::factory()->create();

        $categories = ModelCategory::factory()->count(15)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds, 'fake_id');

        $useCase->execute(
            new GenreUpdateInputDto(
                id: $genreDb->id,
                name: 'Name Updated',
                categoriesId: $categoriesIds
            ),
        );
    }

    public function testTransactionsUpdate()
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new UpdateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $genreDb = Model::factory()->create();

        $categories = ModelCategory::factory()->count(15)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        try {
            $useCase->execute(
                new GenreUpdateInputDto(
                    id: $genreDb->id,
                    name: 'Name Updated',
                    categoriesId: $categoriesIds
                ),
            );

            $this->assertDatabaseHas('genres', [
                'name' => 'Name Updated'
            ]);

            $this->assertDatabaseCount('category_genre', 15);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }

    }
}
