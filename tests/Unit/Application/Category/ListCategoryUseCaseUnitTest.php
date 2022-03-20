<?php

namespace Tests\Unit\Application\Category;

use Core\Application\Category\ListCategoryUseCase;
use Core\Application\DTO\Category\CategoryInputDto;
use Core\Application\DTO\Category\CategoryOutputDto;
use Core\Domain\Entity\Category as CategoryEntity;
use Core\Domain\Repository\CategoryRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Mockery;
use Ramsey\Uuid\Uuid;
use stdClass;

class ListCategoryUseCaseUnitTest extends TestCase
{
    public function testGetById()
    {
        $id = (string) Uuid::uuid4()->toString();
        $categoryName = 'Name Category'; 

        $this->mockEntity = Mockery::mock(CategoryEntity::class, [
            $id,
            $categoryName,
        ]);
        $this->mockEntity->shouldReceive('id')->andReturn($id);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')
                             ->with($id) 
                             ->andReturn($this->mockEntity);
        
        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $id,
        ]);                     

        $useCase = new ListCategoryUseCase($this->mockRepository);
        $reponse = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryOutputDto::class, $reponse);
        $this->assertEquals('Name Category', $reponse->name);
        $this->assertEquals($id, $reponse->id);

        /**
         * Spies
         */

        $this->spyRepository = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spyRepository->shouldReceive('findById')->andReturn($this->mockEntity);
    
        $useCase = new ListCategoryUseCase($this->spyRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);
        $this->spyRepository->shouldHaveReceived('findById');
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}