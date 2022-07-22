<?php

namespace App\Http\Controllers;

use App\Http\Requests\images\ImageDestroyRequest;
use App\Http\Requests\images\ImageIndexRequest;
use App\Http\Requests\images\ImageStoreRequest;
use App\Http\Requests\images\ImageUpdateRequest;
use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Models\Stock;
use App\Traits\Responser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ImageIndexRequest $request)
    {

        $modelQuery = Image::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(ImageResource::collection($modelQuery->get()));
        $models =  $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'images' =>  ImageResource::collection($models->items()),
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
    public function store(ImageStoreRequest $request)
    {
        //
        try {
            $modelData = Image::storeImage($request->file('image'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }
        $createdModel = Image::create($modelData);

        if ($request->has('stock_id')) {
            $stock = Stock::find($request->stock_id);
            $stock->images()->attach($createdModel->id);
        }

        return $this->successResponse(new ImageResource($createdModel));
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
    public function update(ImageUpdateRequest $request)
    {

        try {
            $createdModel = Image::destroyImage($request->id);
            $modelData = Image::storeImage($request->file('image'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }

        $createdModel = Image::where("id", $request->id)->update($modelData);
        return $this->successResponse(new ImageResource(Image::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageDestroyRequest $request)
    {

        try {
            $createdModel = Image::destroyImage($request->id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }
        $stocksCoversIds = $createdModel?->stocks_covers?->pluck('id');
        if ($stocksCoversIds) Stock::whereIn('id', $stocksCoversIds)->update(["cover_image_id" => null]);

        $createdModel->stocks()->detach();
        Image::destroy($createdModel->id);
        return $this->successResponse(new ImageResource($createdModel));
    }
}
