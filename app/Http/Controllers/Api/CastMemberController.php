<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\{
    StoreCastMemberRequest,
    UpdateCastMemberRequest
};
use App\Http\Resources\CastMemberResource;
use Core\Application\CastMember\{
    CreateCastMemberUseCase,
    DeleteCastMemberUseCase,
    ListCastMembersUseCase,
    ListCastMemberUseCase,
    UpdateCastMemberUseCase
};
use Core\Application\DTO\CastMember\CastMemberInputDto;
use Core\Application\DTO\CastMember\Create\CastMemberCreateInputDto;
use Core\Application\DTO\CastMember\List\ListCastMembersInputDto;
use Core\Application\DTO\CastMember\Update\CastMemberUpdateInputDto;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CastMemberController extends Controller
{
    public function index(Request $request, ListCastMembersUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new ListCastMembersInputDto(
                filter: $request->get('filter', ''),
                order: $request->get('order', 'DESC'),
                page: (int) $request->get('page', 1),
                totalPerPage: (int) $request->get('total_page', 15),
            )
        );

        return CastMemberResource::collection(collect($response->items))
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

    public function store(StoreCastMemberRequest $request, CreateCastMemberUseCase $useCase)
    {
        $response = $useCase->execute(
            input: new CastMemberCreateInputDto(
                name: $request->name,
                type: (int) $request->type,
            )
        );

        return (new CastMemberResource($response))
                    ->response()
                    ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ListCastMemberUseCase $useCase, $id)
    {
        $castMember = $useCase->execute(new CastMemberInputDto($id));

        return (new CastMemberResource($castMember))->response();
    }

    public function update(UpdateCastMemberRequest $request, UpdateCastMemberUseCase $useCase, $id)
    {
        $response = $useCase->execute(
            input: new CastMemberUpdateInputDto(
                id: $id,
                name: $request->name,
            )
        );

        return (new CastMemberResource($response))
                    ->response();
    }

    public function destroy(DeleteCastMemberUseCase $useCase, $id)
    {
        $useCase->execute(new CastMemberInputDto($id));

        return response()->noContent();
    }
}
