<?php

namespace App\Http\Controllers;

use App\Http\Requests\icon\IconIndexRequest;
use App\Http\Requests\post\PostDestroyRequest;
use App\Http\Requests\post\PostIndexRequest;
use App\Http\Requests\post\PostStoreRequest;
use App\Http\Requests\post\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Traits\Responser;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PostIndexRequest $request)
    {
        $models = Post::whenisMain(true)
            ->whenVV($request->boolean('vv'))
            ->whenUserId($request->user_id)
            ->orderBy('id', 'desc')
            ->paginate($request->limit ?? 5);
        $data = [
            'total' => (int)$models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int) $models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'posts' =>  PostResource::collection($models->items()),
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
    public function store(PostStoreRequest $request)
    {

        $data = $request->only(
            'body',
            'user_id',
            'stock_id'
        );


        $postMain = null;
        $data['is_main'] = true;
        if ($request->has('post_id')) $data['is_main'] = false;
        if ($request->has('post_id')) $postMain = Post::find($request->post_id);
        if ($request->has('post_id'))  $data['stock_id'] = $postMain->stock_id;
        $createdModel = Post::create($data);
        if ($request->has('post_id')) $postMain->replies()->attach($createdModel->id);
        $createdModel = Post::find($createdModel->id);
        $createdModel->sendNewReplyNotification(true, $postMain, $request->boolean('vv'));
        return $this->successResponse(new PostResource($createdModel));
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
    public function update(PostUpdateRequest $request)
    {
        $data = $request->only(
            'body',
            'is_main',
            'user_id',
            'stock_id'
        );
        $createdModel = Post::whereId($request->id)->update($data);

        return $this->successResponse(new PostResource(Post::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PostDestroyRequest $request)
    {
        $createdModel = Post::find($request->id);
        Post::destroy($request->id);
        return $this->successResponse(new PostResource($createdModel));
    }
}
