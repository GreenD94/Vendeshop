<?php

namespace App\Http\Controllers;

use App\Http\Requests\iconcategory\IconCategoryDestroyRequest;
use App\Http\Requests\iconcategory\IconCategoryIndexRequest;
use App\Http\Requests\iconcategory\IconCategoryStoreRequest;
use App\Http\Requests\iconcategory\IconCategoryUpdateRequest;
use App\Http\Resources\IconCategoryResource;
use App\Models\IconCategory;
use App\Models\Image;
use App\Traits\Responser;
use Illuminate\Http\Request;

class IconCategoryController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(IconCategoryIndexRequest $request)
    {

        $modelQuery = IconCategory::orderBy('id', 'desc');
        if (!$request->page)     return $this->successResponse(IconCategoryResource::collection($modelQuery->get()));
        $stocks =  $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int)$stocks->perPage(),
            'current_page' => (int)$stocks->currentPage(),
            'last_page' => (int) $stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'icon_category' =>  IconCategoryResource::collection($stocks->items()),
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
    public function store(IconCategoryStoreRequest $request)
    {
        try {
            $modelData = Image::storeImage($request->file('image'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }

        $imageModel = Image::create($modelData);
        if ($request->boolean("is_favorite")) {
            $favorite_icon = IconCategory::where("is_favorite", true)->first();
            if ($favorite_icon)  $favorite_icon->is_favorite = false;
            if ($favorite_icon)  $favorite_icon->save();
        }

        $modelData = array_merge($request->only('name', 'is_favorite', 'color'), ["image_id" => $imageModel->id]);
        $createdModel = IconCategory::create($modelData);
        $createdModel->load('image');


        return $this->successResponse(new IconCategoryResource($createdModel));
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
    public function update(IconCategoryUpdateRequest $request)
    {
        $modelData = $request->only('name', 'is_favorite', 'color', 'image_id');
        if ($request->has('image')) {
            try {
                $createdModel = IconCategory::find($request->id);
                Image::destroyImage($createdModel->image->id);
                $modelImageData = Image::storeImage($request->file('image'));
                Image::where("id", $createdModel->image_id)->update($modelImageData);
                $modelData = array_merge($modelData, ["image_id" => $createdModel->image_id]);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
        }

        if ($request->boolean("is_favorite")) {
            $favorite_icon = IconCategory::where("is_favorite", true)->first();
            if ($favorite_icon)  $favorite_icon->is_favorite = false;
            if ($favorite_icon)  $favorite_icon->save();
        }

        IconCategory::where("id", $request->id)->update($modelData);
        return $this->successResponse(new IconCategoryResource(IconCategory::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(IconCategoryDestroyRequest $request)
    {
        try {
            $createdModel = IconCategory::find($request->id);
            Image::destroyImage($createdModel->image_id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }

        $idmiage = $createdModel->image_id;
        IconCategory::destroy($createdModel->id);
        Image::destroy($idmiage);
        return $this->successResponse(new IconCategoryResource($createdModel));
    }
}
