<?php

namespace Tests\Unit\Application\Category;

use Core\Application\Category\DeleteCategoryUseCase;
use Core\Application\DTO\Category\CategoryInputDto;
use Core\Application\DTO\Category\DeleteCategory\CategoryDeleteOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use stdClass;

class DeleteCategoryUseCaseUnitTest extends TestCase
{
    public function testDelete()
    {

        $uuid = (string) Uuid::uuid4()->toString();

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('delete')->andReturn(true);

        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $uuid
        ]);

        $useCase = new DeleteCategoryUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDto::class, $responseUseCase);
        $this->assertTrue($responseUseCase->success);

        /**
         * Spies
         */

        $this->spy = Mockery::spy(stdClass::class, CategoryRepositoryInterface::class);
        $this->spy->shouldReceive('delete')->andReturn(true);
        $useCase = new DeleteCategoryUseCase($this->spy);
        $responseUseCase = $useCase->execute($this->mockInputDto);
        $this->spy->shouldHaveReceived('delete');
    }

    public function testDeleteFalse()
    {

        $uuid = (string) Uuid::uuid4()->toString();

        $this->mockRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $this->mockRepository->shouldReceive('delete')->andReturn(false);

        $this->mockInputDto = Mockery::mock(CategoryInputDto::class, [
            $uuid
        ]);

        $useCase = new DeleteCategoryUseCase($this->mockRepository);
        $responseUseCase = $useCase->execute($this->mockInputDto);

        $this->assertInstanceOf(CategoryDeleteOutputDto::class, $responseUseCase);
        $this->assertFalse($responseUseCase->success);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}