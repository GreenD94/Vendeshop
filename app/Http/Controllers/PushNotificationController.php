<?php

namespace App\Http\Controllers;

use App\Http\Requests\push_notification\PushNotificationDestroyRequest;
use App\Http\Requests\push_notification\PushNotificationIndexRequest;
use App\Http\Requests\push_notification\PushNotificationStoreRequest;
use App\Http\Requests\push_notification\PushNotificationUpdateRequest;
use App\Http\Resources\ComercialResource;
use App\Http\Resources\PushNotificationResource;
use App\Models\Comercial;
use App\Models\Order;
use App\Models\PushNotification;
use App\Models\PushNotificationEvent;
use App\Models\User;
use App\Traits\Responser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PushNotificationController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(PushNotificationIndexRequest $request)
    {


        $modelQuery = PushNotification::orderBy('id', 'desc');
        if (!$request->page) return $this->successResponse(PushNotificationResource::collection($modelQuery->get()));
        $stocks =  $modelQuery->whenUserId($request->user_id)->paginate($request->limit ?? 5);

        $data = [
            'total' => $stocks->total(),
            'per_page' => $stocks->perPage(),
            'current_page' => $stocks->currentPage(),
            'last_page' => $stocks->lastPage(),
            'next_page_url' => $stocks->nextPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'prev_page_url' => $stocks->previousPageUrl(),
            'push_notifications' =>  PushNotificationResource::collection($stocks->items()),
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
    public function store(PushNotificationStoreRequest $request)
    {
        $modelData = $request->only(
            'user_id',
            'tittle',
            'body',
            'is_live',
            'push_notification_event_id',
        );

        $push_notification_event = PushNotificationEvent::find($request->push_notification_event_id);
        $result = ['data' => $push_notification_event, "message" => "push notification event is not valid", "code" => 400];
        if ($push_notification_event->id ==  PushNotificationEvent::$NEWS) $result = PushNotification::sendNewsNotification($modelData, $push_notification_event) ?? $result;
        if ($push_notification_event->id ==  PushNotificationEvent::$NEW_ORDER) $result =  Order::find($request->body["order_id"])->sendNotification($request->is_live);
        if ($push_notification_event->id == PushNotificationEvent::$ORDER_STATE_CHANGE) $result =  Order::find($request->body["order_id"])->sendStatusChangedNotification($request->is_live);


        return $this->successResponse($result['data'], $result['message'], $result['code']);
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
    public function update(PushNotificationUpdateRequest $request)
    {
        $createdModel = PushNotification::whereId($request->id)->update($request->only(
            'user_id',
            'tittle',
            'body',
            'is_check',
            'is_new',
            'push_notification_event_id',
        ));

        return $this->successResponse(new PushNotificationResource(PushNotification::find($request->id)));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PushNotificationDestroyRequest $request)
    {
        $createdModel = PushNotification::find($request->id);
        PushNotification::destroy($request->id);
        return $this->successResponse(new PushNotificationResource($createdModel));
    }
}
