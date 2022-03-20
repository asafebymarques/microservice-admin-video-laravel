<?php

namespace Core\Application\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Application\DTO\Category\{
    CategoryInputDto,
    CategoryOutputDto
};

class ListCategoryUseCase
{
    protected $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    public function execute(CategoryInputDto $input) : CategoryOutputDto
    {
        $category = $this->categoryRepositoryInterface->findById($input->id);

        return new CategoryOutputDto(
            id: $category->id,
            name: $category->name,
            description: $category->description,
            is_active: $category->isActive,
            created_at: $category->createdAt(),
        );
    }
}