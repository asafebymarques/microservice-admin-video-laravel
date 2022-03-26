<?php

namespace Tests\Unit\Application\Genre;

use Core\Application\DTO\Genre\GenreInputDto;
use Core\Application\DTO\Genre\Delete\DeleteGenreOutputDto;
use Core\Application\Genre\DeleteGenreUseCase;
use Core\Domain\Entity\Genre as EntityGenre;
use Core\Domain\Repository\GenreRepositoryInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;

class DeleteGenreUseCaseUnitTest extends TestCase
{
    public function test_delete()
    {
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')->andReturn(true);

        $mockInputDto = Mockery::mock(GenreInputDto::class, [$uuid]);

        $useCase = new DeleteGenreUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(DeleteGenreOutputDto::class, $response);
        $this->assertTrue($response->success);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, GenreRepositoryInterface::class);
        $this->spy->shouldReceive('delete')->andReturn(true);
        $useCase = new DeleteGenreUseCase($this->spy);
        $useCase->execute($mockInputDto);
        $this->spy->shouldHaveReceived('delete');
    }

    public function test_delete_fail()
    {
        $uuid = (string) Uuid::uuid4();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('delete')->andReturn(false);

        $mockInputDto = Mockery::mock(GenreInputDto::class, [$uuid]);

        $useCase = new DeleteGenreUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertFalse($response->success);
    }

    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }
}
