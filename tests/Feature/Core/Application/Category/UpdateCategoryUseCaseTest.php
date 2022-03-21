<?php

namespace Tests\Feature\Core\Application\Category;


use App\Models\Category as Model;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Application\Category\UpdateCategoryUseCase;
use Core\Application\DTO\Category\UpdateCategory\CategoryUpdateInputDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UpdateCategoryUseCaseTest extends TestCase
{
    public function test_update()
    {
        $categoryDb = Model::factory()->create();

        $repository = new CategoryEloquentRepository(new Model());
        $useCase = new UpdateCategoryUseCase($repository);
        $responseUseCase = $useCase->execute(
            new CategoryUpdateInputDto(
               id: $categoryDb->id,
               name: 'name updated',
            )
        );

        $this->assertEquals('name updated', $responseUseCase->name);
        $this->assertEquals($categoryDb->description, $responseUseCase->description);

        $this->assertDatabaseHas('categories', [
            'name' => $responseUseCase->name,
        ]);
    }
}
