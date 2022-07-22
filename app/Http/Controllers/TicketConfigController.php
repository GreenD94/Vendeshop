<?php

namespace App\Http\Controllers;

use App\Http\Requests\ticket_config\TicketConfigDestroyRequest;
use App\Http\Requests\ticket_config\TicketConfigIndexRequest;
use App\Http\Requests\ticket_config\TicketConfigStoreRequest;
use App\Http\Requests\ticket_config\TicketConfigUpdateRequest;
use App\Http\Resources\TicketConfigResource;
use App\Models\TicketConfig;
use App\Traits\Responser;
use Illuminate\Http\Request;

class TicketConfigController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TicketConfigIndexRequest $request)
    {
        $modelQuery = TicketConfig::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(TicketConfigResource::collection($modelQuery->get()));

        $stocks = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $stocks->total(),
            'per_page' => (int) $stocks->perPage(),
            'current_page' => (int) $stocks->currentPage(),
            'last_page' => (int)$stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'ticket_configs' =>  TicketConfigResource::collection($stocks->items()),
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
    public function store(TicketConfigStoreRequest $request)
    {

        $modelData = $request->only(
            'is_active',
            'return_percentage',
            'return_price',
            'minimum_spend'
        );
        if ($request->boolean('is_active')) {
            TicketConfig::where('id', '>', 0)->update([
                'is_active' => false
            ]);
        }
        $isFirst = TicketConfig::where('id', '>', 0)->count() == 0;
        if ($isFirst) $modelData['is_active'] = true;
        $createdModel = TicketConfig::create($modelData);

        return $this->successResponse(new TicketConfigResource($createdModel));
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
    public function update(TicketConfigUpdateRequest $request)
    {
        $modelData = $request->only(
            'is_active',
            'return_percentage',
            'return_price',
            'minimum_spend'
        );
        if ($request->boolean('is_active')) {
            TicketConfig::where('id', '>', 0)->update([
                'is_active' => false
            ]);
        }
        TicketConfig::where("id", $request->id)->update($modelData);
        return $this->successResponse(new TicketConfigResource(TicketConfig::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketConfigDestroyRequest $request)
    {
        $createdModel = TicketConfig::find($request->id);
        $isAlone = TicketConfig::where('id', '>', 0)->count() == 1;
        if ($isAlone) return $this->errorResponse([], "no se puede borrar La configuracion de puntos, ya que es el unico", 422);
        TicketConfig::destroy($request->id);
        if ($createdModel->is_active) TicketConfig::where("is_active", false)->update(["is_active" => true]);
        return $this->successResponse(new TicketConfigResource($createdModel));
    }
}
