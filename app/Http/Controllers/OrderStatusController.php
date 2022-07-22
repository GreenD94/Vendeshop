<?php

namespace App\Http\Controllers;

use App\Http\Requests\order_status\OrderStatusDestroyRequest;
use App\Http\Requests\order_status\OrderStatusIndexRequest;
use App\Http\Requests\order_status\OrderStatusStoreRequest;
use App\Http\Requests\order_status\OrderStatusUpdateRequest;
use App\Http\Resources\OrderStatusResource;
use App\Models\OrderStatus;
use App\Traits\Responser;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(OrderStatusIndexRequest $request)
    {
        $modelQuery = OrderStatus::orderBy('id', 'asc');
        return $this->successResponse(OrderStatusResource::collection($modelQuery->get()));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderStatusStoreRequest $request)
    {
        $modelData = $request->only('name');
        $createdModel = OrderStatus::create($modelData);
        return $this->successResponse(new OrderStatusResource($createdModel));
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
    public function update(OrderStatusUpdateRequest $request)
    {
        $modelData = $request->only('name');
        OrderStatus::where("id", $request->id)->update($modelData);
        return $this->successResponse(new OrderStatusResource(OrderStatus::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderStatusDestroyRequest $request)
    {
        $createdModel = OrderStatus::find($request->id);
        OrderStatus::destroy($createdModel->id);
        return $this->successResponse(new OrderStatusResource($createdModel));
    }
}
