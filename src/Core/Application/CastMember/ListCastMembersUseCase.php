<?php

namespace Core\Application\CastMember;

use Core\Domain\Repository\CastMemberRepositoryInterface;
use Core\Application\DTO\CastMember\List\ListCastMembersInputDto;
use Core\Application\DTO\CastMember\List\ListCastMembersOutputDto;

class ListCastMembersUseCase
{
    protected $repository;

    public function __construct(CastMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function execute(ListCastMembersInputDto $input): ListCastMembersOutputDto
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPerPage,
        );

        return new ListCastMembersOutputDto(
            items: $response->items(),
            total: $response->total(),
            current_page: $response->currentPage(),
            last_page: $response->lastPage(),
            first_page: $response->firstPage(),
            per_page: $response->perPage(),
            to: $response->to(),
            from: $response->from(),
        );
    }
}
