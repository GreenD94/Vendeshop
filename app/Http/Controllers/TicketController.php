<?php

namespace App\Http\Controllers;

use App\Http\Requests\ticket\TicketDestroyRequest;
use App\Http\Requests\ticket\TicketIndexRequest;
use App\Http\Requests\ticket\TicketStoreRequest;
use App\Http\Requests\ticket\TicketUpdateRequest;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use App\Traits\Responser;
use Illuminate\Http\Request;


class TicketController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TicketIndexRequest $request)
    {
        $modelQuery = Ticket::orderBy('id', 'desc')->whenUserId($request->user_id);
        $tickets_total =   floatval(number_format((float) $modelQuery->sum('value'), 2, '.', ''));
        if (!$request->page) return $this->successResponse([
            'tikcets' => TicketResource::collection($modelQuery->get()),
            'tickets_total' =>  $tickets_total,

        ]);

        $models = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'tikcets' =>  TicketResource::collection($models->items()),
            'tickets_total' =>  $tickets_total
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
    public function store(TicketStoreRequest $request)
    {
        $data = $request->only(
            'user_id',
            'value',
            'expiration_time',
        );

        $createdModel = Ticket::create($data);

        return $this->successResponse(new TicketResource(Ticket::find($createdModel->id)));
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
    public function update(TicketUpdateRequest $request)
    {
        $data = $request->only(
            'user_id',
            'value',
            'expiration_time',
            'is_used',
            'is_active',

        );
        $createdModel = Ticket::whereId($request->id)->update($data);

        return $this->successResponse(new TicketResource(Ticket::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(TicketDestroyRequest $request)
    {
        $createdModel = Ticket::find($request->id);
        Ticket::destroy($request->id);
        return $this->successResponse(new TicketResource($createdModel));
    }
}
