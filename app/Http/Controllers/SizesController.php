<?php

namespace App\Http\Controllers;

use App\Http\Requests\sizes\SizesDestroyRequest;
use App\Http\Requests\sizes\SizesIndexRequest;
use App\Http\Requests\sizes\SizesStoreRequest;
use App\Http\Requests\sizes\SizesUpdateRequest;
use App\Http\Resources\SizeResource;
use App\Models\Size;
use App\Traits\Responser;
use Illuminate\Http\Request;

class SizesController extends Controller
{

    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(SizesIndexRequest $request)
    {
        $modelQuery = Size::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(SizeResource::collection($modelQuery->get()));

        $models = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'sizes' =>  SizeResource::collection($models->items()),
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
    public function store(SizesStoreRequest $request)
    {
        $data = $request->only(
            'size',
        );

        $createdModel = Size::create($data);

        return $this->successResponse(new SizeResource($createdModel));
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
    public function update(SizesUpdateRequest $request)
    {
        $data = $request->only(
            'size',
        );
        $createdModel = Size::whereId($request->id)->update($data);

        return $this->successResponse(new SizeResource(Size::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(SizesDestroyRequest $request)
    {
        $createdModel = Size::find($request->id);
        Size::destroy($request->id);
        return $this->successResponse(new SizeResource($createdModel));
    }
}
