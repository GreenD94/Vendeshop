<?php

namespace App\Http\Controllers;

use App\Http\Requests\image360\Image360DestroyRequest;
use App\Http\Requests\image360\Image360IndexRequest;
use App\Http\Requests\image360\Image360StoreRequest;
use App\Http\Requests\image360\Image360UpdateRequest;
use App\Http\Resources\Animation360Resource;
use App\Models\Image;
use App\Models\Image360i;
use App\Models\Stock;
use App\Traits\Responser;
use Illuminate\Http\Request;

class Image360Controller extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Image360IndexRequest $request)
    {
        $animation360 = Image360i::onlyAnimation360()
            ->animation360Id($request->animation_360_id)
            ->whenName($request->name)
            ->getAnimation360(
                [
                    "paginate" =>  $request->limit ?? 5,
                    "page" => $request->page,
                    "frame_limit" => $request->frame_limit ?? 0
                ]
            );

        if ($request->page) $animation360["animation"] = Animation360Resource::ChatRooms($animation360["animation"]);
        $data = $request->page ? $animation360 : [
            'message' => 'Success',
            'animation'    => Animation360Resource::ChatRooms($animation360)
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
    public function store(Image360StoreRequest $request)
    {

        $animation_360_id = null;

        foreach ($request->file('images') as $imagefile) {


            try {
                $modelData = Image::storeImage($imagefile);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
            $imageModel = Image::create($modelData);
            $data = $request->only(
                'name',
            );

            $data['image_id'] = $imageModel->id;
            if (!$animation_360_id) $data['is_main'] = true;

            $frame = Image360i::create($data);
            if (!$animation_360_id) $animation_360_id =  $frame->id;
            Image360i::where('id', $frame->id)->update(['animation_360_id' => $animation_360_id]);
        }
        // try {
        //     $modelData = Image::storeImage($request->file('image'));
        // } catch (\Throwable $th) {
        //     return $this->errorResponse($th->getTrace(), $th->getMessage());
        // }
        // $imageModel = Image::create($modelData);

        // $data = $request->only(
        //     'name',
        //     'animation_360_id'
        // );
        // $data['image_id'] = $imageModel->id;
        // if (!$request->has('animation_360_id')) $data['is_main'] = true;
        // $frame = Image360i::create($data);
        // if (!$request->has('animation_360_id')) Image360i::where('id', $frame->id)->update(['animation_360_id' => $frame->id]);
        // $frame->refresh();
        // return $this->successResponse(new Animation360Resource($frame));
        if (!$animation_360_id) $this->successResponse([], 'no se ha podido subir ni 1 imagen');
        return $this->successResponse(new Animation360Resource(Image360i::find($animation_360_id)));
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
    public function update(Image360UpdateRequest $request)
    {
        $modelData = $request->only('name', 'image_id', 'animation_360_id');
        if ($request->has('image')) {
            try {
                $createdModel = Image360i::find($request->id);
                Image::destroyImage($createdModel->image->id);
                $modelImageData = Image::storeImage($request->file('image'));
                Image::where("id", $createdModel->image_id)->update($modelImageData);
                $modelData = array_merge($modelData, ["image_id" => $createdModel->image_id]);
            } catch (\Throwable $th) {
                return $this->errorResponse($th->getTrace(), $th->getMessage());
            }
        }


        Image360i::where("id", $request->id)->update($modelData);
        return $this->successResponse(new Animation360Resource(Image360i::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image360DestroyRequest $request)
    {
        if ($request->animation_360_id) {
            $animation = Image360i::where('animation_360_id', $request->animation_360_id)->get();
            foreach ($animation as $key => $frame) {
                try {
                    $createdModel = $frame;
                    Image::destroyImage($createdModel->image_id);
                } catch (\Throwable $th) {
                    return $this->errorResponse(null, $th->getMessage());
                }

                $idmiage = $createdModel->image_id;
                Stock::where('animation_id', $request->animation_360_id)->update(["animation_id" => null]);
                Image360i::destroy($createdModel->id);
                Image::destroy($idmiage);
            }
            return $this->successResponse([]);
        }
        try {
            $createdModel = Image360i::find($request->id);
            Image::destroyImage($createdModel->image_id);
        } catch (\Throwable $th) {
            return $this->errorResponse(null, $th->getMessage());
        }

        $idmiage = $createdModel->image_id;
        Stock::where('animation_id', $request->animation_360_id)->update(["animation_id" => null]);
        Image360i::destroy($createdModel->id);
        Image::destroy($idmiage);
        return $this->successResponse(new Animation360Resource($createdModel));
    }
}
