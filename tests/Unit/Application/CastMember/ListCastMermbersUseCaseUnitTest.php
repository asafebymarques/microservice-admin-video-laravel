<?php

namespace Tests\Unit\Application\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Application\DTO\CastMember\List\{
    ListCastMembersInputDto,
    ListCastMembersOutputDto
};
use Core\Application\CastMember\ListCastMembersUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Tests\Unit\Application\UseCaseTrait;

class ListCastMermbersUseCaseUnitTest extends TestCase
{
    use UseCaseTrait;

    public function test_list()
    {
        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('paginate')
                            ->once()
                            ->andReturn($this->mockPagination());

        $useCase = new ListCastMembersUseCase($mockRepository);

        $mockInputDto = Mockery::mock(ListCastMembersInputDto::class, [
            'filter', 'desc', 1, 15
        ]);

        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(ListCastMembersOutputDto::class, $response);

        Mockery::close();
    }
}
