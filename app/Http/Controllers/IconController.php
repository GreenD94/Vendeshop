<?php

namespace App\Http\Controllers;

use App\Http\Requests\icon\IconDestroyRequest;
use App\Http\Requests\icon\IconIndexRequest;
use App\Http\Requests\icon\IconStoreRequest;
use App\Http\Requests\icon\IconUpdateRequest;
use App\Http\Resources\IconResource;
use App\Models\Icon;
use App\Models\Image;
use App\Traits\Responser;
use Illuminate\Http\Request;

class IconController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IconIndexRequest $request)
    {

        $modelQuery = Icon::orderBy('id', 'desc');
        if (!$request->page)     return $this->successResponse(IconResource::collection($modelQuery->get()));
        $stocks =  $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int)$stocks->perPage(),
            'current_page' => (int)$stocks->currentPage(),
            'last_page' => (int) $stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'icons' =>  IconResource::collection($stocks->items()),
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
    public function store(IconStoreRequest $request)
    {
        try {
            $modelData = Image::storeImage($request->file('image'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }

        $imageModel = Image::create($modelData);
        if ($request->boolean("is_favorite")) {
            $favorite_icon = Icon::where("is_favorite", true)->first();
            if ($favorite_icon)  $favorite_icon->is_favorite = false;
            if ($favorite_icon)  $favorite_icon->save();
        }

        $modelData = array_merge($request->only('name', 'is_favorite', 'color'), ["image_id" => $imageModel->id]);
        $createdModel = Icon::create($modelData);
        $createdModel->load('image');


        return $this->successResponse(new IconResource($createdModel));
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
    public function update(IconUpdateRequest $request)
    {
        $modelData = $request->only('name', 'is_favorite', 'color', 'image_id');
        if ($request->has('image')) {
            try {
                $createdModel = Icon::find($request->id);
                Image::destroyImage($createdModel->image->id);
                $modelImageData = Image::storeImage($request->file('image'));
                Image::where("id", $createdModel->image_id)->update($modelImageData);
                $modelData = array_merge($modelData, ["image_id" => $createdModel->image_id]);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
        }

        if ($request->boolean("is_favorite")) {
            $favorite_icon = Icon::where("is_favorite", true)->first();
            if ($favorite_icon)  $favorite_icon->is_favorite = false;
            if ($favorite_icon)  $favorite_icon->save();
        }

        Icon::where("id", $request->id)->update($modelData);
        return $this->successResponse(new IconResource(Icon::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IconDestroyRequest $request)
    {
        try {
            $createdModel = Icon::find($request->id);
            Image::destroyImage($createdModel->image_id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }

        $idmiage = $createdModel->image_id;
        Icon::destroy($createdModel->id);
        Image::destroy($idmiage);
        return $this->successResponse(new IconResource($createdModel));
    }
}
