<?php

namespace App\Http\Controllers;

use App\Http\Requests\stocks\ImageSubscriptionDestroyRequest;
use App\Http\Requests\stocks\ImageSubscriptionStoreRequest;
use App\Models\Stock;
use App\Traits\Responser;
use Illuminate\Http\Request;

class StockImageSubscription extends Controller
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
    public function store(ImageSubscriptionStoreRequest $request)
    {
        //
        Stock::find($request->stock_id)->images()->attach($request->image_id);
        return $this->successResponse();
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
    public function update(Request $request)
    {
        //
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
