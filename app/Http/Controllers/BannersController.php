<?php

namespace App\Http\Controllers;

use App\Http\Requests\banners\BannerDestroyRequest;
use App\Http\Requests\banners\BannerIndexRequest;
use App\Http\Requests\banners\BannerStoreRequest;
use App\Http\Requests\banners\BannerUpdateRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use App\Models\Image;
use App\Traits\Responser;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(BannerIndexRequest $request)
    {
        $bannerQuery = Banner::orderBy('id', 'desc');
        $banners = $bannerQuery->get();
        return   $this->successResponse(Banner::groupByNumber($banners));

        if (!$request->page) return $this->successResponse(BannerResource::collection($bannerQuery->get()));
        $stocks = $bannerQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int)$stocks->perPage(),
            'current_page' => (int)$stocks->currentPage(),
            'last_page' => (int) $stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'banners' =>  BannerResource::collection($stocks->items()),
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
    public function store(BannerStoreRequest $request)
    {

        try {
            $modelData = Image::storeImage($request->file('image'));
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getTrace(), $th->getMessage());
        }

        $imageModel = Image::create($modelData);
        $modelData = array_merge($request->only(
            'group_number',
            'name',
            'is_favorite',
            'image_id',
        ), ["image_id" => $imageModel->id]);
        $createdModel = Banner::create($modelData);
        $createdModel->load('image');


        return $this->successResponse(new BannerResource($createdModel));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
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
    public function update(BannerUpdateRequest $request)
    {

        $modelData = $request->only(
            'name',
            'is_favorite',
            'image_id',
            'group_number'
        );
        if ($request->has('image')) {
            try {
                $createdModel = Banner::find($request->id);
                Image::destroyImage($createdModel->image->id);
                $modelImageData = Image::storeImage($request->file('image'));
                Image::where("id", $createdModel->image_id)->update($modelImageData);
                $modelData = array_merge($modelData, ["image_id" => $createdModel->image_id]);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
        }


        Banner::where("id", $request->id)->update($modelData);
        return $this->successResponse(new BannerResource(Banner::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(BannerDestroyRequest $request)
    {

        try {
            $createdModel = Banner::find($request->id);
            Image::destroyImage($createdModel->image_id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }

        $idmiage = $createdModel->image_id;
        Banner::destroy($createdModel->id);
        Image::destroy($idmiage);
        return $this->successResponse(new BannerResource($createdModel));
    }
}
