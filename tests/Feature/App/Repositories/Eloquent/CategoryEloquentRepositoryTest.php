<?php

namespace Tests\Feature\App\Repositories\Eloquent;

use Core\Domain\Exception\NotFoundException;
use App\Models\Category as Model;
use App\Models\Category;
use App\Repositories\Eloquent\CategoryEloquentRepository;
use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CategoryEloquentRepositoryTest extends TestCase
{

    protected $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new CategoryEloquentRepository(new Model());
    }

    public function testInsert()
    {
        $entity = new EntityCategory(
            name: 'Teste'
        );

        $response = $this->repository->insert($entity);

        $this->assertInstanceOf(CategoryRepositoryInterface::class, $this->repository);
        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertDatabaseHas('categories', [
            'name' => $entity->name
        ]);
    }

    public function testFindById()
    {
        $category = Model::factory()->create();

        $response = $this->repository->findById($category->id);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertEquals($category->id, $response->id());
    }

    public function testFindByIdNotFound()
    {
       try {
            $this->repository->findById('fakeValue');

            $this->assertTrue(false);
       } catch (\Throwable $th) {
           $this->assertInstanceOf(NotFoundException::class, $th);
       }
    }

    public function testFindAll()
    {
        Model::factory()->count(10)->create();

        $response = $this->repository->findAll();

        $this->assertCount(10, $response);
    }

    public function testPaginate()
    {
        Model::factory()->count(20)->create();

        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(15, $response->items());
    }

    public function testPaginateWithout()
    {
        $response = $this->repository->paginate();

        $this->assertInstanceOf(PaginationInterface::class, $response);
        $this->assertCount(0, $response->items());
    }

    public function testUpdateIdNotFound()
    {
        try {
            $category = new EntityCategory(name: 'test');

            $this->repository->update($category);

            $this->assertTrue(false);
       } catch (\Throwable $th) {
           $this->assertInstanceOf(NotFoundException::class, $th);
       }
    }

    public function testUpdate()
    {
        $categoryDb = Category::factory()->create();

        $category = new EntityCategory(
            id: $categoryDb->id,
            name: 'Updated Name'
        );

        $response = $this->repository->update($category);

        $this->assertInstanceOf(EntityCategory::class, $response);
        $this->assertNotEquals($response->name, $categoryDb->name);
        $this->assertEquals('Updated Name', $response->name);
    }

    public function testDeleteIdNotFound()
    {
        try {
            $this->repository->delete('fake_id');

            $this->assertTrue(false);
       } catch (\Throwable $th) {
           $this->assertInstanceOf(NotFoundException::class, $th);
       }
    }

    public function testDelete()
    {
        $categoryDb = Category::factory()->create();

        $response = $this->repository
                            ->delete($categoryDb->id);

        $this->assertTrue($response);
    }
}
