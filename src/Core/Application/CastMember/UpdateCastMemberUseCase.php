<?php

namespace Core\Application\CastMember;

use Core\Application\DTO\CastMember\Update\{
    CastMemberUpdateInputDto,
    CastMemberUpdateOutputDto
};
use Core\Domain\Repository\CastMemberRepositoryInterface;

class UpdateCastMemberUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(CastMemberUpdateInputDto $input): CastMemberUpdateOutputDto
    {
        $entity = $this->repository->findById($input->id);
        $entity->update(name: $input->name);

        $this->repository->update($entity);

        return new CastMemberUpdateOutputDto(
            id: $entity->id(),
            name: $entity->name,
            type: $entity->type->value,
            created_at: $entity->createdAt(),
        );
    }
}
