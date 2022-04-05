<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGenreRequest;
use App\Http\Requests\UpdateGenreRequest;
use App\Http\Resources\GenreResource;
use Core\Application\DTO\Genre\Create\GenreCreateInputDto;
use Core\Application\DTO\Genre\GenreInputDto;
use Core\Application\DTO\Genre\List\{
    ListGenresInputDto
};
use Core\Application\DTO\Genre\Update\GenreUpdateInputDto;
use Core\Application\Genre\{
    CreateGenreUseCase,
    DeleteGenreUseCase,
    ListGenresUseCase,
    ListGenreUseCase,
    UpdateGenreUseCase
};
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GenreController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, ListGenresUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListGenresInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPage: (int) $request->get('totalPage', 15),
            )
        );

        return GenreResource::collection(collect($response->items))
        ->additional([
            'meta' => [
                'total' => $response->total,
                'current_page' => $response->current_page,
                'last_page' => $response->last_page,
                'first_page' => $response->first_page,
                'per_page' => $response->per_page,
                'to' => $response->to,
                'from' => $response->from,
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGenreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGenreRequest $request, CreateGenreUseCase $createGenreUseCase)
    {
        $response = $createGenreUseCase->execute(
            input: new GenreCreateInputDto(
                name: $request->name,
                isActive: (bool) $request->is_active,
                categoriesId: $request->categories_ids,
            )
        );

        return (new GenreResource($response))
               ->response()
               ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(ListGenreUseCase $listGenreUseCase, $id)
    {
        $response = $listGenreUseCase->execute(
            input: new GenreInputDto(
                id: $id
            )
        );

        return (new GenreResource($response))
        ->response()
        ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGenreRequest $request, UpdateGenreUseCase $updateGenreUseCase, $id)
    {
        $response = $updateGenreUseCase->execute(
            input: new GenreUpdateInputDto(
                id: $id,
                name: $request->name,
                categoriesId: $request->categories_ids
            )
        );

        return (new GenreResource($response))
                    ->response()
                    ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(DeleteGenreUseCase $deleteGenreUseCase, $id)
    {
        $deleteGenreUseCase->execute(
            input: new GenreInputDto(
                id: $id
            )
        );

        return response()->noContent();
    }
}
