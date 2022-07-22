<?php

namespace App\Http\Controllers;

use App\Http\Requests\categories\CategoryDeleteRequest;
use App\Http\Requests\categories\CategoryIndexRequest;
use App\Http\Requests\categories\CategoryStoreRequest;
use App\Http\Requests\categories\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\Image;
use App\Traits\Responser;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(CategoryIndexRequest $request)
    {
        $modelQuery = Category::orderBy('name');
        if (!$request->page)     return $this->successResponse(CategoryResource::collection($modelQuery->get()));
        $stocks = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int)$stocks->perPage(),
            'current_page' => (int)$stocks->currentPage(),
            'last_page' => (int) $stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'categories' =>  CategoryResource::collection($stocks->items()),
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
    public function store(CategoryStoreRequest $request)
    {


        try {
            $modelData = Image::storeImage($request->file('image'));
            $imageModel = Image::create($modelData);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }

        $modelData = array_merge($request->only(
            'name',
            'is_main',
            "color",

        ), ["image_id" => $imageModel->id]);

        $createdModel = Category::create($modelData);
        $createdModel->load("image");


        return $this->successResponse(new CategoryResource($createdModel));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit()
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
    public function update(CategoryUpdateRequest $request)
    {

        $modelData = $request->only(
            'name',
            'is_main',
            "color",
            'image_id',
        );
        if ($request->has('image')) {
            try {
                $createdModel = Category::find($request->id);
                Image::destroyImage($createdModel->image->id);
                $modelImageData = Image::storeImage($request->file('image'));
                Image::where("id", $createdModel->image_id)->update($modelImageData);
                $modelData = array_merge($modelData, ["image_id" => $createdModel->image_id]);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
        }

        Category::whereId($request->id)->update($modelData);

        return $this->successResponse(new CategoryResource(Category::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CategoryDeleteRequest $request)
    {
        try {
            $createdModel = Category::find($request->id);
            if ($createdModel->isLoMasTop() || $createdModel->isOfertas()) return $this->errorResponse([], "no se puedee eliminar la Categoria (" . $request->id . ") por que causara daños en el sistema. porfavor contactar al personal tecnico", 422);
            Image::destroyImage($createdModel->image_id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }
        Category::destroy($request->id);
        Image::destroy($createdModel->image_id);
        return $this->successResponse(new CategoryResource($createdModel));
    }
}
