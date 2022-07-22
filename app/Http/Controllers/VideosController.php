<?php

namespace App\Http\Controllers;

use App\Http\Requests\videos\VideoDestroyRequest;
use App\Http\Requests\videos\VideoIndexRequest;
use App\Http\Requests\videos\VideoStoreRequest;
use App\Http\Requests\videos\VideoUpdateRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\VideoResource;
use App\Models\Video;
use App\Traits\Responser;
use Illuminate\Http\Request;

class VideosController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(VideoIndexRequest $request)
    {
        $modelQuery = Video::orderBy('id', 'desc')->whenIsInformation($request->boolean('is_information'));
        if (!$request->page) return $this->successResponse(VideoResource::collection($modelQuery->get()));
        $models =  $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'videos' =>  VideoResource::collection($models->items()),
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
    public function store(VideoStoreRequest $request)
    {
        $data = $request->only(
            'name',
            'url',
            'is_information'
        );
        $createdModel = Video::create($data);
        return $this->successResponse(new VideoResource($createdModel));
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
    public function update(VideoUpdateRequest $request)
    {
        $data = $request->only(
            'name',
            'url',
            'is_information'
        );
        $createdModel = Video::whereId($request->id)->update($data);

        return $this->successResponse(new VideoResource(Video::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(VideoDestroyRequest $request)
    {
        $createdModel = Video::find($request->id);
        Video::destroy($request->id);
        return $this->successResponse(new VideoResource($createdModel));
    }
}
