<?php

namespace App\Http\Controllers;


use App\Http\Requests\payuconfig\PayuConfigDestroyRequest;
use App\Http\Requests\payuconfig\PayuConfigIndexRequest;
use App\Http\Requests\payuconfig\PayuConfigStoreRequest;
use App\Http\Requests\payuconfig\PayuConfigUpdateRequest;

use App\Http\Resources\PayuConfigResource;

use App\Models\PayuConfig;
use App\Traits\Responser;
use Illuminate\Http\Request;

class PayuConfigController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PayuConfigIndexRequest $request)
    {
        $modelQuery = PayuConfig::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(PayuConfigResource::collection($modelQuery->get()));

        $stocks = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int) $stocks->perPage(),
            'current_page' => (int) $stocks->currentPage(),
            'last_page' => (int)$stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'ads' =>  PayuConfigResource::collection($stocks->items()),
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
    public function store(PayuConfigStoreRequest $request)
    {

        $modelData =  $request->only(
            'is_active',
            'api_key',
            'api_login',
            'merchant_id',
            'is_test',
            'payments_custom_url',
            'reports_custom_url',
            'account_id',
            'description',
            'tax_value',
            'tax_return_base',
            'currency',
            'installments_number'
        );
        if ($request->boolean('is_active')) {
            PayuConfig::where('id', '>', 0)->update([
                'is_active' => false
            ]);
        }
        $createdModel = PayuConfig::create($modelData);



        return $this->successResponse(new PayuConfigResource($createdModel));
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
    public function update(PayuConfigUpdateRequest $request)
    {

        $modelData = $request->only(
            'is_active',
            'api_key',
            'api_login',
            'merchant_id',
            'is_test',
            'payments_custom_url',
            'reports_custom_url',
            'account_id',
            'description',
            'tax_value',
            'tax_return_base',
            'currency',
            'installments_number'
        );
        if ($request->boolean('is_active')) {
            PayuConfig::where('id', '>', 0)->update([
                'is_active' => false
            ]);
        }
        PayuConfig::where("id", $request->id)->update($modelData);
        return $this->successResponse(new PayuConfigResource(PayuConfig::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PayuConfigDestroyRequest $request)
    {

        $createdModel = PayuConfig::find($request->id);
        PayuConfig::destroy($request->id);
        return $this->successResponse(new PayuConfigResource($createdModel));
    }
}
