<?php

namespace Core\Application\Genre;

use Core\Application\DTO\Genre\Update\{
    GenreUpdateInputDto,
    GenreUpdateOutputDto
};
use Core\Application\Interfaces\TransactionInterface;
use Core\Domain\Exception\NotFoundException;
use Core\Domain\Repository\{
    CategoryRepositoryInterface,
    GenreRepositoryInterface
};

class UpdateGenreUseCase
{
    protected $repository;
    protected $transaction;
    protected $categoryRepository;

    public function __construct(
        GenreRepositoryInterface $genreRepositoryInterface,
        TransactionInterface $transactionInterface,
        CategoryRepositoryInterface $categoryRepositoryInterface
    ) {
        $this->repository = $genreRepositoryInterface;
        $this->transaction = $transactionInterface;
        $this->categoryRepository = $categoryRepositoryInterface;
    }

    public function execute(GenreUpdateInputDto $input): GenreUpdateOutputDto
    {
        $genre = $this->repository->findById($input->id);

        try {
            $genre->update(
                name: $input->name,
            );

            foreach ($input->categoriesId as $categoryId) {
                $genre->addCategory($categoryId);
            }

            $this->validateCategoriesId($input->categoriesId);

            $genreDb = $this->repository->update($genre);

            $this->transaction->commit();

            return new GenreUpdateOutputDto(
                id: (string) $genreDb->id,
                name: $genreDb->name,
                is_active: $genreDb->isActive,
                created_at: $genreDb->createdAt(),
            );
        } catch (\Throwable $th) {
            $this->transaction->rollback();
            throw $th;
        }
    }

    public function validateCategoriesId(array $categoriesId = [])
    {
        $categoriesDb = $this->categoryRepository->getIdsListIds($categoriesId);

        $arrayDiff = array_diff($categoriesId, $categoriesDb);

        if (count($arrayDiff)) {
            $msg = sprintf(
                '%s %s not found',
                count($arrayDiff) > 1 ? 'Categories' : 'Category',
                implode(', ', $arrayDiff)
            );

            throw new NotFoundException($msg);
        }
    }
}
