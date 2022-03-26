<?php

namespace Core\Application\Genre;

use Core\Application\DTO\Genre\{
    GenreInputDto,
    GenreOutputDto
};
use Core\Domain\Repository\GenreRepositoryInterface;

class ListGenreUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $genreRepositoryInterface)
    {
        $this->repository = $genreRepositoryInterface;
    }

    public function execute(GenreInputDto $input): GenreOutputDto
    {
        $genre = $this->repository->findById(id: $input->id);

        return new GenreOutputDto(
            id: (string) $genre->id,
            name: $genre->name,
            is_active: $genre->isActive,
            created_at: $genre->createdAt(),
        );
    }
}
