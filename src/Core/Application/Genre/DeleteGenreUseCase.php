<?php

namespace Core\Application\Genre;

use Core\Application\DTO\Genre\GenreInputDto;
use Core\Application\DTO\Genre\Delete\DeleteGenreOutputDto;
use Core\Domain\Repository\GenreRepositoryInterface;

class DeleteGenreUseCase
{
    protected $repository;

    public function __construct(GenreRepositoryInterface $genreRepositoryInterface)
    {
        $this->repository = $genreRepositoryInterface;
    }

    public function execute(GenreInputDto $input): DeleteGenreOutputDto
    {
        $success = $this->repository->delete(id: $input->id);

        return new DeleteGenreOutputDto(
            success: $success
        );
    }
}
