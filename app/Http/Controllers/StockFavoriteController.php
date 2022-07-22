<?php

namespace App\Http\Controllers;

use App\Http\Requests\stocks\ImageSubscriptionDestroyRequest;
use App\Http\Requests\stocks\ImageSubscriptionStoreRequest;
use App\Http\Requests\stocks\StockFavoriteUpdateRequest;
use App\Models\Stock;
use App\Models\User;
use App\Traits\Responser;
use Illuminate\Http\Request;

class StockFavoriteController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(request $request)
    {
        //

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
    public function update(StockFavoriteUpdateRequest $request)
    {
        $user = User::find($request->has('user_id') ? $request->user_id : auth()->id());
        $user->favorite_stock()->toggle([$request->stock_id]);
        $is_favorite =  !!$user->favorite_stock->firstWhere('id', $request->stock_id);
        return $this->successResponse(['is_favorite' => $is_favorite]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ImageSubscriptionDestroyRequest $request)
    {
        Stock::find($request->stock_id)->images()->detach($request->image_id);
        return $this->successResponse();
    }
}
