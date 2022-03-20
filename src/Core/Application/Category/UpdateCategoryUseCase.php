<?php

namespace Core\Application\Category;

use Core\Application\DTO\Category\UpdateCategory\CategoryUpdateInputDto;
use Core\Application\DTO\Category\UpdateCategory\CategoryUpdateOutputDto;
use Core\Domain\Repository\CategoryRepositoryInterface;

class UpdateCategoryUseCase
{
    protected $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    public function execute(CategoryUpdateInputDto $input): CategoryUpdateOutputDto
    {
        $category = $this->categoryRepositoryInterface->findById($input->id);

        $category->update(
            name: $input->name,
            description: $input->description ?? $category->description,
        );

        $categoryUpdated = $this->categoryRepositoryInterface->update($category);

        return new CategoryUpdateOutputDto(
            id: $categoryUpdated->id,
            name: $categoryUpdated->name,
            description: $categoryUpdated->description,
            isActive: $categoryUpdated->isActive,
            created_at: $categoryUpdated->createdAt(),
        );
    }
}