<?php

namespace Tests\Feature\Core\Application\Genre;

use App\Models\Genre as Model;
use App\Models\Category as ModelCategory;
use App\Repositories\Eloquent\{
    CategoryEloquentRepository,
    GenreEloquentRepository
};
use App\Repositories\Transaction\DBTransaction;
use Core\Application\DTO\Genre\Create\GenreCreateInputDto;
use Core\Application\Genre\CreateGenreUseCase;
use Core\Domain\Exception\NotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CreateGenreUseCaseTest extends TestCase
{

    public function test_insert()
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        $useCase->execute(
            new GenreCreateInputDto(
                name: 'teste',
                categoriesId: $categoriesIds
            ),
        );

        $this->assertDatabaseHas('genres', [
            'name' => 'teste'
        ]);

        $this->assertDatabaseCount('category_genre', 10);
    }

    public function test_exception_insert_genre_with_categories_ids_invalid()
    {
        $this->expectException(NotFoundException::class);

        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();
        array_push($categoriesIds, 'fake_id');

        $useCase->execute(
            new GenreCreateInputDto(
                name: 'teste',
                categoriesId: $categoriesIds
            ),
        );
    }

    public function testTransactionsInsert()
    {
        $repository = new GenreEloquentRepository(new Model());
        $repositoryCategory = new CategoryEloquentRepository(new ModelCategory());

        $useCase = new CreateGenreUseCase(
            $repository,
            new DBTransaction(),
            $repositoryCategory
        );

        $categories = ModelCategory::factory()->count(10)->create();
        $categoriesIds = $categories->pluck('id')->toArray();

        try {
            $useCase->execute(
                new GenreCreateInputDto(
                    name: 'teste',
                    categoriesId: $categoriesIds
                ),
            );

            $this->assertDatabaseHas('genres', [
                'name' => 'teste'
            ]);
        } catch (\Throwable $th) {
            $this->assertDatabaseCount('genres', 0);
            $this->assertDatabaseCount('category_genre', 0);
        }

    }
}
