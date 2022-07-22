<?php

namespace App\Http\Controllers;

use App\Http\Requests\colors\ColorsDestroyRequest;
use App\Http\Requests\colors\ColorsIndexRequest;
use App\Http\Requests\colors\ColorsStoreRequest;
use App\Http\Requests\colors\ColorsUpdateRequest;
use App\Http\Resources\ColorResource;
use App\Models\Color;
use App\Traits\Responser;
use Illuminate\Http\Request;

class ColorsController extends Controller
{

    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ColorsIndexRequest $request)
    {
        $modelQuery = Color::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(ColorResource::collection($modelQuery->get()));

        $models = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'colors' =>  ColorResource::collection($models->items()),
        ];
        return $this->successResponse($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ColorsStoreRequest $request)
    {
        $data = $request->only(
            'hex',
        );

        $createdModel = Color::create($data);

        return $this->successResponse(new ColorResource($createdModel));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ColorsUpdateRequest $request)
    {
        $data = $request->only(
            'hex',

        );
        $createdModel = Color::whereId($request->id)->update($data);

        return $this->successResponse(new ColorResource(Color::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ColorsDestroyRequest $request)
    {
        $createdModel = Color::find($request->id);
        Color::destroy($request->id);
        return $this->successResponse(new ColorResource($createdModel));
    }
}
