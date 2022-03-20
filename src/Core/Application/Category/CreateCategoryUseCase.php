<?php

namespace Core\Application\Category;

use Core\Domain\Entity\Category;
use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Application\DTO\Category\CreateCategory\{
    CategoryCreateInputDto,
    CategoryCreateOutputDto
};

class CreateCategoryUseCase
{
    protected $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    public function execute(CategoryCreateInputDto $input) : CategoryCreateOutputDto
    {
        $category = new Category(
            name: $input->name,
            description: $input->description,
            isActive: $input->isActive
        );

        $newCategory = $this->categoryRepositoryInterface->insert($category);

        return new CategoryCreateOutputDto(
            id: $newCategory->id(),
            name: $newCategory->name,
            description: $newCategory->description,
            is_active: $category->isActive,
            created_at: $newCategory->createdAt(),
        );
    }
}