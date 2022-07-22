<?php

namespace App\Http\Controllers;

use App\Http\Requests\push_notification_event\PushNotificationEventDestroyRequest;
use App\Http\Requests\push_notification_event\PushNotificationEventIndexRequest;
use App\Http\Requests\push_notification_event\PushNotificationEventStoreRequest;
use App\Http\Requests\push_notification_event\PushNotificationEventUpdateRequest;
use App\Http\Resources\PushNotificationEventResource;
use App\Models\PushNotificationEvent;
use App\Traits\Responser;
use Illuminate\Http\Request;

class PushNotificationEventsController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PushNotificationEventIndexRequest $request)
    {

        $modelQuery = PushNotificationEvent::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(PushNotificationEventResource::collection($modelQuery->get()));
        $models =  $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'push_notification_event' =>  PushNotificationEventResource::collection($models->items()),
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
    public function store(PushNotificationEventStoreRequest $request)
    {
        $createdModel = PushNotificationEvent::create($request->only(
            'name',
        ));
        return $this->successResponse(new PushNotificationEventResource($createdModel));
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
    public function update(PushNotificationEventUpdateRequest $request)
    {
        $createdModel = PushNotificationEvent::whereId($request->id)->update($request->only(
            'name',
        ));

        return $this->successResponse(new PushNotificationEventResource(PushNotificationEvent::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushNotificationEventDestroyRequest $request)
    {
        $createdModel = PushNotificationEvent::find($request->id);
        $cantDelete = $createdModel->isNews() || $createdModel->isNewOrder() || $createdModel->isOrderstateChange() || $createdModel->isNewReply();
        if ($cantDelete) return $this->errorResponse([], "no se puedee eliminar el Evento (" . $request->id . ") por que causara daños en el sistema. porfavor contactar al personal tecnico", 422);
        PushNotificationEvent::destroy($request->id);
        return $this->successResponse(new PushNotificationEventResource($createdModel));
    }
}
