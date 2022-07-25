<?php

namespace App\Http\Controllers;

use App\Http\Requests\comercial\ComercialDestroyRequest;
use App\Http\Requests\comercial\ComercialIndexRequest;
use App\Http\Requests\comercial\ComercialStoreRequest;
use App\Http\Requests\comercial\ComercialUpdateRequest;
use App\Http\Resources\ComercialResource;
use App\Models\Comercial;
use App\Models\Image;
use App\Traits\Responser;
use Illuminate\Http\Request;

class ComercialController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ComercialIndexRequest $request)
    {

        $modelQuery = Comercial::orderBy('id', 'desc');
        if (!$request->page)     return $this->successResponse(ComercialResource::collection($modelQuery->get()));
        $model =  $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $model->total(),
            'per_page' => (int)$model->perPage(),
            'current_page' => (int)$model->currentPage(),
            'last_page' => (int) $model->lastPage(),
            'next_page_url' => $model->nextPageUrl(),
            'prev_page_url' => $model->previousPageUrl(),
            'prev_page_url' => $model->previousPageUrl(),
            'comercials' =>  ComercialResource::collection($model->items()),
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
    public function store(ComercialStoreRequest $request)
    {
        try {
            $modelData = Image::storeImage($request->file('image'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }

        $imageModel = Image::create($modelData);


        $modelData = array_merge($request->only('name', 'tittle'), ["image_id" => $imageModel->id]);

        $createdModel = Comercial::create($modelData);
        $createdModel->load('image');


        return $this->successResponse(new ComercialResource($createdModel));
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
    public function update(ComercialUpdateRequest $request)
    {
        $modelData = $request->only('name',  'image_id', 'tittle');
        if ($request->has('image')) {
            try {
                $createdModel = Comercial::find($request->id);
                Image::destroyImage($createdModel->image->id);
                $modelImageData = Image::storeImage($request->file('image'));
                Image::where("id", $createdModel->image_id)->update($modelImageData);
                $modelData = array_merge($modelData, ["image_id" => $createdModel->image_id]);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
        }


        Comercial::where("id", $request->id)->update($modelData);
        return $this->successResponse(new ComercialResource(Comercial::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ComercialDestroyRequest $request)
    {

        try {
            $createdModel = Comercial::find($request->id);
            if ($createdModel->isWelcomeNew() || $createdModel->isWelcomeOld()) return $this->errorResponse([], "no se puedee eliminar el comercial (" . $request->id . ") por que causara daños en el sistema. porfavor contactar al personal tecnico", 422);
            Image::destroyImage($createdModel->image_id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }

        $idmiage = $createdModel->image_id;
        Comercial::destroy($createdModel->id);
        Image::destroy($idmiage);
        return $this->successResponse(new ComercialResource($createdModel));
    }
}
