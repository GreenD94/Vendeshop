<?php

namespace App\Http\Controllers;

use App\Http\Requests\shipping_cost\ShippingCostDestroyRequest;
use App\Http\Requests\shipping_cost\ShippingCostIndexRequest;
use App\Http\Requests\shipping_cost\ShippingCostStoreRequest;
use App\Http\Requests\shipping_cost\ShippingCostUpdateRequest;
use App\Http\Resources\ShippingCostResource;
use App\Models\ShippingCost;
use App\Traits\Responser;
use Illuminate\Http\Request;

class ShippingCostController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ShippingCostIndexRequest $request)
    {
        $modelQuery = ShippingCost::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(ShippingCostResource::collection($modelQuery->get()));

        $stocks = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int) $stocks->perPage(),
            'current_page' => (int) $stocks->currentPage(),
            'last_page' => (int)$stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'shipping_cost' =>  ShippingCostResource::collection($stocks->items()),
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
    public function store(ShippingCostStoreRequest $request)
    {

        $modelData = $request->only(
            'is_active',
            'price',
            'price_percentage',
            'poblacion_origen',
            'poblacion_destino',
            'departamento_destino',
            'tipo_envio',
            'd2021_paq',
            'd2021_msj',
            'd1kg_msj',
            'd2kg_msj',
            'd3kg_msj',
            'd4kg_msj',
            'd5kg_msj'
        );
        if ($request->boolean('is_active')) {
            ShippingCost::where('id', '>', 0)->update([
                'is_active' => false
            ]);
        }
        $isFirst = ShippingCost::where('id', '>', 0)->count() == 0;
        if ($isFirst) $modelData['is_active'] = true;
        $createdModel = ShippingCost::create($modelData);

        return $this->successResponse(new ShippingCostResource($createdModel));
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
    public function update(ShippingCostUpdateRequest $request)
    {
        $modelData = $request->only(
            'is_active',
            'price',
            'price_percentage',
            'poblacion_origen',
            'poblacion_destino',
            'departamento_destino',
            'tipo_envio',
            'd2021_paq',
            'd2021_msj',
            'd1kg_msj',
            'd2kg_msj',
            'd3kg_msj',
            'd4kg_msj',
            'd5kg_msj'
        );
        if ($request->boolean('is_active')) {
            ShippingCost::where('id', '>', 0)->update([
                'is_active' => false
            ]);
        }
        ShippingCost::where("id", $request->id)->update($modelData);
        return $this->successResponse(new ShippingCostResource(ShippingCost::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShippingCostDestroyRequest $request)
    {
        $createdModel = ShippingCost::find($request->id);
        $isAlone = ShippingCost::where('id', '>', 0)->count() == 1;
        if ($isAlone) return $this->errorResponse([], "no se puede borrar El Costo De Envio, ya que es el unico", 422);
        ShippingCost::destroy($request->id);
        if ($createdModel->is_active) ShippingCost::where("is_active", false)->update(["is_active" => true]);
        return $this->successResponse(new ShippingCostResource($createdModel));
    }
}
