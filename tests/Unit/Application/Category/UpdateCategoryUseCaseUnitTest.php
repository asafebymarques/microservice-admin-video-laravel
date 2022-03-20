<?php

namespace Tests\Unit\Application\Category;

use Core\Domain\Entity\Category as EntityCategory;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Application\Category\UpdateCategoryUseCase;
use Core\Application\DTO\Category\UpdateCategory\{
    CategoryUpdateInputDto,
    CategoryUpdateOutputDto
};
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class UpdateCategoryUseCaseUnitTest extends TestCase
{
    public function testRenameCategory()
    {
        $uuid = (string) Uuid::uuid4()->toString();
        $categoryName = 'Name';
        $categoryDesc = 'Desc';

        $this->mockEntity = Mockery::mock(EntityCategory::class, [
            $uuid, $categoryName, $categoryDesc
        ]);
        $this->mockEntity->shouldReceive('update');
        $this->mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->mockRepository->shouldReceive('update')->andReturn($this->mockEntity);


        $this->mockInputDto = Mockery::mock(CategoryUpdateInputDto::class, [
            $uuid, 
            'New Name',
        ]);

        $useCase = new UpdateCategoryUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryUpdateOutputDto::class, $responseUseCase);

        /**
         * Spies
         */

        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('findById')->andReturn($this->mockEntity);
        $this->spy->shouldReceive('update')->andReturn($this->mockEntity);
        $useCase = new UpdateCategoryUseCase($this->spy);
        $useCase->execute($this->mockInputDto);
        $this->spy->shouldHaveReceived('findById');
        $this->spy->shouldHaveReceived('update');

        Mockery::close();
    }
}
