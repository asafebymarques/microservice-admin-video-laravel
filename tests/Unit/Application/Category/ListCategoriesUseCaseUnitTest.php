<?php

namespace Tests\Unit\Application\Category;

use Core\Application\Category\ListCategoriesUseCase;
use Core\Application\DTO\Category\ListCategories\ListCategoriesInputDto;
use Core\Application\DTO\Category\ListCategories\ListCategoriesOutputDto;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListCategoriesUseCaseUnitTest extends TestCase
{
    public function testListCategoriesEmpty()
    {
        $this->mockerPagination = $this->mockPagination();

        $this->mockerRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockerRepository->shouldReceive('paginate')->andReturn($this->mockerPagination);

        $this->mockerInputDto = Mockery::mock(ListCategoriesInputDto::class, ['filter', 'desc']);
        
        $useCase = new ListCategoriesUseCase($this->mockerRepository);
        $responseUseCase = $useCase->execute($this->mockerInputDto);

        $this->assertCount(0, $responseUseCase->items);
        $this->assertInstanceOf(ListCategoriesOutputDto::class, $responseUseCase);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('paginate')->andReturn($this->mockerPagination);
        $useCase = new ListCategoriesUseCase($this->spy);
        $useCase->execute($this->mockerInputDto);
        $this->spy->shouldHaveReceived('paginate');
    }

    public function testListCategories()
    {
        $register = new stdClass();
        $register->id = 'sdf';
        $register->name = 'name';
        $register->description = 'description';
        $register->is_active = 'is_active';
        $register->created_at = 'created_at';
        $register->updated_at = 'updated_at';
        $register->deleted_at = 'deleted_at';

        $this->mockerPagination = $this->mockPagination([
            $register,
        ]);

        $this->mockerRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockerRepository->shouldReceive('paginate')->andReturn($this->mockerPagination);

        $this->mockerInputDto = Mockery::mock(ListCategoriesInputDto::class, ['filter', 'desc']);
        
        $useCase = new ListCategoriesUseCase($this->mockerRepository);
        $responseUseCase = $useCase->execute($this->mockerInputDto);

        $this->assertCount(1, $responseUseCase->items);
        $this->assertInstanceOf(stdClass::class, $responseUseCase->items[0]);
        $this->assertInstanceOf(ListCategoriesOutputDto::class, $responseUseCase);
    }

    protected function mockPagination(array $items = [])
    {
        $this->mockerPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockerPagination->shouldReceive('items')->andReturn($items);
        $this->mockerPagination->shouldReceive('total')->andReturn(0);
        $this->mockerPagination->shouldReceive('firstPage')->andReturn(0);
        $this->mockerPagination->shouldReceive('lastPage')->andReturn(0);
        $this->mockerPagination->shouldReceive('perPage')->andReturn(0);
        $this->mockerPagination->shouldReceive('to')->andReturn(0);
        $this->mockerPagination->shouldReceive('from')->andReturn(0);

        return $this->mockerPagination;
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}