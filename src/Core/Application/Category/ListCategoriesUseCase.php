<?php

namespace Core\Application\Category;

use Core\Domain\Repository\CategoryRepositoryInterface;
use Core\Application\DTO\Category\ListCategories\{
    ListCategoriesInputDto,
    ListCategoriesOutputDto,
};

class ListCategoriesUseCase
{
    protected $categoryRepositoryInterface;

    public function __construct(CategoryRepositoryInterface $categoryRepositoryInterface)
    {
        $this->categoryRepositoryInterface = $categoryRepositoryInterface;
    }

    public function execute(ListCategoriesInputDto $input) : ListCategoriesOutputDto
    {
        $categories = $this->categoryRepositoryInterface->paginate(
            filter: $input->filter,
            order: $input->order,
            page: $input->page,
            totalPage: $input->totalPage
        );

        /*return new ListCategoriesOutputDto(
            items: array_map(function ($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->name,
                    'description' => $data->description,
                    'is_active' => (bool) $data->is_active,
                    'created_at' => (string) $data->created_at,
                ];
            }, $categories->items()),
            total: $categories->total(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from(),
        );*/

        return new ListCategoriesOutputDto(
            items: $categories->items(),
            total: $categories->total(),
            last_page: $categories->lastPage(),
            first_page: $categories->firstPage(),
            per_page: $categories->perPage(),
            to: $categories->to(),
            from: $categories->from(),
        );
    } 
}