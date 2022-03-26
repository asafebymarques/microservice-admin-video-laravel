<?php

namespace Core\Application\Genre;

use Core\Application\DTO\Genre\List\{
    ListGenresInputDto,
    ListGenresOutputDto
};
use Core\Domain\Repository\GenreRepositoryInterface;

class ListGenresUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $genreRepositoryInterface)
    {
        $this->repository = $genreRepositoryInterface;
    }

    public function execute(ListGenresInputDto $input): ListGenresOutputDto
    {
        $response = $this->repository->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        return new ListGenresOutputDto(
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
