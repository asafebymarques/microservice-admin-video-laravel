<?php

namespace Tests\Unit\Application\Genre;

use Core\Application\DTO\Genre\List\{
    ListGenresInputDto,
    ListGenresOutputDto
};
use Core\Application\Genre\ListGenresUseCase;
use Core\Domain\Repository\GenreRepositoryInterface;
use Core\Domain\Repository\PaginationInterface;
use Mockery;
use PHPUnit\Framework\TestCase;
use stdClass;

class ListGenresUseCaseUnitTest extends TestCase
{
    public function test_usecase()
    {
        $this->mockerPagination = $this->mockPagination();

        $mockRepository = Mockery::mock(stdClass::class, GenreRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')->andReturn($this->mockerPagination);


        $mockDtoInput = Mockery::mock(ListGenresInputDto::class, [
            'teste', 'desc', 1, 15
        ]);

        $useCase = new ListGenresUseCase($mockRepository);
        $response = $useCase->execute($mockDtoInput);

        $this->assertInstanceOf(ListGenresOutputDto::class, $response);

        /**
         * Spies
         */
        $this->spy = Mockery::spy(stdClass::class, GenreRepositoryInterface::class);
        $this->spy->shouldReceive('paginate')->andReturn($this->mockPagination());
        $useCase = new ListGenresUseCase($this->spy);
        $response = $useCase->execute($mockDtoInput);
        $this->spy->shouldHaveReceived('paginate');

        Mockery::close();
    }

    protected function mockPagination(array $items = [])
    {
        $this->mockerPagination = Mockery::mock(stdClass::class, PaginationInterface::class);
        $this->mockerPagination->shouldReceive('items')->andReturn($items);
        $this->mockerPagination->shouldReceive('total')->andReturn(0);
        $this->mockerPagination->shouldReceive('currentPage')->andReturn(0);
        $this->mockerPagination->shouldReceive('firstPage')->andReturn(0);
        $this->mockerPagination->shouldReceive('lastPage')->andReturn(0);
        $this->mockerPagination->shouldReceive('perPage')->andReturn(0);
        $this->mockerPagination->shouldReceive('to')->andReturn(0);
        $this->mockerPagination->shouldReceive('from')->andReturn(0);

        return $this->mockerPagination;
    }
}
