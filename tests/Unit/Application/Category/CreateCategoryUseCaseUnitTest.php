<?php

namespace Tests\Unit\Application\Category;

use Ramsey\Uuid\Uuid;
use Core\Application\Category\CreateCategoryUseCase;
use Core\Application\DTO\Category\CreateCategory\CategoryCreateInputDto;
use Core\Application\DTO\Category\CreateCategory\CategoryCreateOutputDto;
use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class CreateCategoryUseCaseUnitTest extends TestCase
{
    public function testCreateNewCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'Name Category'; 

        $this->mockEntity = Mockery::mock(Category::class, [
            $uuid,
            $categoryName,
        ]);

        $this->mockEntity->shouldReceive('id')->andReturn($uuid);
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('insert')->andReturn($this->mockEntity);

        $this->mockInputDto = Mockery::mock(CategoryCreateInputDto::class, [
            $categoryName,
        ]);

        $useCase = new CreateCategoryUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryCreateOutputDto::class, $responseUseCase);
        $this->assertEquals($categoryName, $responseUseCase->name);
        $this->assertEquals('', $responseUseCase->description);
        $this->assertEquals(true, $responseUseCase->is_active);
        
        /**
         * Spies
         */

        $this->spyRepository = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spyRepository->shouldReceive('insert')->andReturn($this->mockEntity);
    
        $useCase = new CreateCategoryUseCase($this->spyRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);
        $this->spyRepository->shouldHaveReceived('insert');

        Mockery::close();
    }
}