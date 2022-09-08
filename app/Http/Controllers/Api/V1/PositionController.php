<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Http\Resources\PositionResource;
use App\Models\Position;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $positions = Position::all();

        return PositionResource::collection($positions)
                               ->response()
                               ->setStatusCode(Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StorePositionRequest $request
     *
     * @return JsonResponse
     */
    public function store(StorePositionRequest $request): JsonResponse
    {
        $position = Position::factory()
                            ->create([
                                'name' => $request->name,
                            ]);

        return PositionResource::make($position)
                               ->response()
                               ->setStatusCode(Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param UpdatePositionRequest $request
     * @param Position $position
     *
     * @return JsonResponse
     */
    public function show(): JsonResponse
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdatePositionRequest $request
     * @param Position $position
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        $position->save(
            $request->all()
        );

        return PositionResource::make($position)
                               ->response()
                               ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
