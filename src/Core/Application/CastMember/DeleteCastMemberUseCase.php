<?php

namespace Core\Application\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Application\DTO\CastMember\CastMemberInputDto;
use Core\Application\DTO\CastMember\Delete\DeleteCastMemberOutputDto;

class DeleteCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberInputDto $input): DeleteCastMemberOutputDto
    {
        $hasDeleted = $this->repository->delete($input->id);

        return new DeleteCastMemberOutputDto(
            success: $hasDeleted
        );
    }
}
