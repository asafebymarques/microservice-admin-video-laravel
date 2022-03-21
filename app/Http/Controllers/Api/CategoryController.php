<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    StoreCategoryRequest,
    UpdateCategoryRequest
};
use App\Http\Resources\CategoryResource;
use Core\Application\Category\{
    CreateCategoryUseCase,
    DeleteCategoryUseCase,
    ListCategoriesUseCase,
    ListCategoryUseCase,
    UpdateCategoryUseCase
};
use Core\Application\DTO\Category\CategoryInputDto;
use Core\Application\DTO\Category\CreateCategory\CategoryCreateInputDto;
use Core\Application\DTO\Category\ListCategories\ListCategoriesInputDto;
use Core\Application\DTO\Category\UpdateCategory\CategoryUpdateInputDto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    public function index(Request $request, ListCategoriesUseCase $listCategoriesUseCase)
    {
        $response = $listCategoriesUseCase->execute(
            input: new ListCategoriesInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('totalPage', 15),
            )
        );

        return CategoryResource::collection(collect($response->items))
                                                ->additional([
                                                    'meta' => [
                                                        'total' => $response->total,
                                                        'last_page' => $response->last_page,
                                                        'first_page' => $response->first_page,
                                                        'per_page' => $response->per_page,
                                                        'to' => $response->to,
                                                        'from' => $response->from,
                                                    ]
                                                ]);
    }

    public function store(StoreCategoryRequest $request, CreateCategoryUseCase $createCategoryUseCase)
    {
        $response = $createCategoryUseCase->execute(
            input: new CategoryCreateInputDto(
                name: $request->name,
                description: $request->description ?? '',
                isActive: (bool) $request->is_active ?? true,
            )
        );

        return (new CategoryResource(collect($response)))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCategoryUseCase $listCategoryUseCase, $id)
    {
        $category = $listCategoryUseCase->execute(new CategoryInputDto($id));

        return (new CategoryResource(collect($category)))
                        ->response()
                        ->setStatusCode(Response::HTTP_OK);
    }

    public function update(UpdateCategoryRequest $request, UpdateCategoryUseCase $updateCategoryUseCase, $id)
    {
        $response = $updateCategoryUseCase->execute(
            input: new CategoryUpdateInputDto(
                id: $id,
                name: $request->name,
            )
        );

        return (new CategoryResource(collect($response)))
                        ->response()
                        ->setStatusCode(Response::HTTP_OK);
    }

    public function destroy(DeleteCategoryUseCase $deleteCategoryUseCase, $id)
    {
        $deleteCategoryUseCase->execute(new CategoryInputDto(
            id: $id,
        ));

        return response()->json([], Response::HTTP_NO_CONTENT);
    }
}
