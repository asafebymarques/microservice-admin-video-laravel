<?php

namespace Core\Application\Category;

use Core\Application\DTO\Category\CategoryInputDto;
use Core\Application\DTO\Category\DeleteCategory\CategoryDeleteOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;

class DeleteCategoryUseCase
{
    protected $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    public function execute(CategoryInputDto $input): CategoryDeleteOutputDto
    {
        $responseDelete = $this->categoryRepositoryInterface->delete($input->id);

        return new CategoryDeleteOutputDto(
            success: $responseDelete
        );
    }
}