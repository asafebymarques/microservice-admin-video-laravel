<?php

namespace Tests\Unit\Application\Genre;

use Core\Application\DTO\Genre\Create\{
    GenreCreateInputDto,
    GenreCreateOutputDto
};
use Core\Application\Genre\CreateGenreUseCase;
use Core\Application\Interfaces\TransactionInterface;
use Core\Domain\Repository\{
    GenreRepositoryInterface,
    CategoryRepositoryInterface
};
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Exception\NotFoundException;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class CreateGenreUseCaseUnitTest extends TestCase
{
    public function test_create()
    {
        $uuid = (string) Uuid::uuid4();

        $useCase = new CreateGenreUseCase($this->mockRepository($uuid), $this->mockTransaction(), $this->mockCategoryRepository($uuid));
        $response = $useCase->execute($this->mockCreateInputDto([$uuid]));

        $this->assertInstanceOf(GenreCreateOutputDto::class, $response);

         /**
         * Spies
         */

        $this->spyRepository = Mockery::spy(stdClass::class, GenreRepositoryInterface::class);
        $this->spyRepository->shouldReceive('insert')->andReturn($this->mockEntity($uuid));

        $useCase = new CreateGenreUseCase($this->spyRepository, $this->mockTransaction(), $this->mockCategoryRepository($uuid));
        $useCase->execute($this->mockCreateInputDto([$uuid]));
        $this->spyRepository->shouldHaveReceived('insert');
    }

    public function test_create_categories_notfound()
    {
        $this->expectException(NotFoundException::class);

        $uuid = (string) Uuid::uuid4();

        $useCase = new CreateGenreUseCase($this->mockRepository($uuid), $this->mockTransaction(), $this->mockCategoryRepository($uuid));
        $useCase->execute($this->mockCreateInputDto([$uuid, 'fake_id']));
    }

    private function mockEntity(string $uuid)
    {
        $mockEntity = Mockery::mock(EntityGenre::class, [
            'teste', new ValueObjectUuid($uuid), true, []
        ]);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));

        return $mockEntity;
    }

    private function mockRepository(string $uuid)
    {
        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('insert')->andReturn($this->mockEntity($uuid));

        return $mockRepository;
    }

    private function mockTransaction()
    {
        $mockTransaction = Mockery::mock(stdClass::class, TransactionInterface::class);
        $mockTransaction->shouldReceive('commit');
        $mockTransaction->shouldReceive('rollback');

        return $mockTransaction;
    }

    private function mockCategoryRepository(string $uuid)
    {
        $mockCategoryRepository = Mockery::mock(stdClass::class, CategoryRepositoryInterface::class);
        $mockCategoryRepository->shouldReceive('getIdsListIds')->andReturn([$uuid]);

        return $mockCategoryRepository;
    }

    private function mockCreateInputDto(array $categoriesId)
    {
        $mockCreateInputDto = Mockery::mock(GenreCreateInputDto::class, [
            'teste', $categoriesId, true
        ]);

        return $mockCreateInputDto;
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
