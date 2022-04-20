<?php

namespace Core\Application\CastMember;

use Core\Application\DTO\CastMember\Create\CastMemberCreateInputDto;
use Core\Application\DTO\CastMember\Create\CastMemberCreateOutputDto;
use Core\Domain\Entity\CastMember;
use Core\Domain\Enum\CastMemberType;
use Core\Domain\Repository\CastMemberRepositoryInterface;

class CreateCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $castMemberRepositoryInterface)
    {
        $this->repository = $castMemberRepositoryInterface;
    }

    public function execute(CastMemberCreateInputDto $input): CastMemberCreateOutputDto
    {
        $entity = new CastMember(
            name: $input->name,
            type: $input->type == 1 ? CastMemberType::DIRECTOR : CastMemberType::ACTOR,
        );

        $this->repository->insert($entity);

        return new CastMemberCreateOutputDto(
            id: $entity->id(),
            name: $entity->name,
            type: $input->type,
            created_at: $entity->createdAt(),
        );
    }
}
