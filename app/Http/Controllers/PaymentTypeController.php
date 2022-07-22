<?php

namespace App\Http\Controllers;

use App\Http\Requests\payment_type\PaymentTypeDestroyRequest;
use App\Http\Requests\payment_type\PaymentTypeIndexRequest;
use App\Http\Requests\payment_type\PaymentTypeStoreRequest;
use App\Http\Requests\payment_type\PaymentTypeUpdateRequest;
use App\Http\Resources\PaymentTypeResource;
use App\Models\PaymentType;
use App\Traits\Responser;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PaymentTypeIndexRequest $request)
    {
        $modelQuery = PaymentType::orderBy('id', 'desc');
        return $this->successResponse(PaymentTypeResource::collection($modelQuery->get()));
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
    public function store(PaymentTypeStoreRequest $request)
    {

        $modelData = $request->only('name');
        $createdModel = PaymentType::create($modelData);
        return $this->successResponse(new PaymentTypeResource($createdModel));
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
    public function update(PaymentTypeUpdateRequest $request)
    {

        $modelData = $request->only('name');

        PaymentType::where("id", $request->id)->update($modelData);
        return $this->successResponse(new PaymentTypeResource(PaymentType::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentTypeDestroyRequest $request)
    {
        $model = PaymentType::find($request->id);

        PaymentType::destroy($request->id);
        return $this->successResponse(new PaymentTypeResource($model));
    }
}
