<?php

namespace Tests\Unit\Application\CastMember;

use Core\Domain\Entity\CastMember as EntityCastMember;
use Core\Application\DTO\CastMember\Update\{
    CastMemberUpdateInputDto,
    CastMemberUpdateOutputDto
};
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Domain\ValueObject\Uuid as ValueObjectUuid;
use Core\Application\CastMember\UpdateCastMemberUseCase;
use Mockery;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid as RamseyUuid;
use stdClass;

class UpdateCastMermberUseCaseUnitTest extends TestCase
{
    public function test_update()
    {
        $uuid = (string) RamseyUuid::uuid4();

        $mockEntity = Mockery::mock(EntityCastMember::class, [
            'name',
            CastMemberType::ACTOR,
            new ValueObjectUuid($uuid)
        ]);
        $mockEntity->shouldReceive('id')->andReturn($uuid);
        $mockEntity->shouldReceive('createdAt')->andReturn(date('Y-m-d H:i:s'));
        $mockEntity->shouldReceive('update');

        $mockRepository = Mockery::mock(stdClass::class, CastMemberRepositoryInterface::class);
        $mockRepository->shouldReceive('findById')
                            ->times(1)
                            ->with($uuid)
                            ->andReturn($mockEntity);
        $mockRepository->shouldReceive('update')
                            ->once()
                            ->andReturn($mockEntity);

        $mockInputDto = Mockery::mock(CastMemberUpdateInputDto::class, [
            $uuid, 'new name'
        ]);

        $useCase = new UpdateCastMemberUseCase($mockRepository);
        $response = $useCase->execute($mockInputDto);

        $this->assertInstanceOf(CastMemberUpdateOutputDto::class, $response);

        Mockery::close();
    }
}
