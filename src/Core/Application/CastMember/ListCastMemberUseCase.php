<?php

namespace Core\Application\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Application\DTO\CastMember\{
    CastMemberInputDto,
    CastMemberOutputDto
};

class ListCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberInputDto $input): CastMemberOutputDto
    {
        $castMember = $this->repository->findById($input->id);

        return new CastMemberOutputDto(
            id: $castMember->id(),
            name: $castMember->name,
            type: $castMember->type->value,
            created_at: $castMember->createdAt(),
        );
    }
}
