<?php

namespace App\Http\Controllers;

use App\Http\Requests\ribbon\RibbonDestroyRequest;
use App\Http\Requests\ribbon\RibbonIndexRequest;
use App\Http\Requests\ribbon\RibbonStoreRequest;
use App\Http\Requests\ribbon\RibbonUpdateRequest;
use App\Http\Resources\ImageResource;
use App\Http\Resources\RibbonResource;
use App\Models\Image;
use App\Models\Ribbon;
use App\Models\Stock;
use App\Traits\Responser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RibbonsController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(RibbonIndexRequest $request)
    {

        $modelQuery = Ribbon::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(RibbonResource::collection($modelQuery->get()));
        $models = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'ribbons' =>  RibbonResource::collection($models->items()),
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
    public function store(RibbonStoreRequest $request)
    {
        //
        try {
            $modelData = Image::storeImage($request->file('image'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }
        $imageModel = Image::create($modelData);
        $createdModel = Ribbon::create(["image_id" => $imageModel->id]);
        $createdModel->load('image');


        return $this->successResponse(new RibbonResource($createdModel));
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
    public function update(RibbonUpdateRequest $request)
    {


        try {
            $createdModel = Ribbon::find($request->id);
            Image::destroyImage($createdModel->image->id);
            $modelData = Image::storeImage($request->file('image'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }


        Image::where("id", $createdModel->image_id)->update($modelData);
        return $this->successResponse(new RibbonResource(Ribbon::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RibbonDestroyRequest $request)
    {

        try {
            $ribbonModel = Ribbon::find($request->id);
            $createdModel = Image::destroyImage($ribbonModel->image_id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }

        $idmiage = $ribbonModel->image_id;

        Stock::where("ribbon_id", $request->id)->update(["ribbon_id" => null]);
        Ribbon::destroy($ribbonModel->id);
        Image::destroy($idmiage);
        return $this->successResponse(['id' =>  $idmiage]);
    }
}
