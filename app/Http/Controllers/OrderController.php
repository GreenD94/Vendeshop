<?php

namespace App\Http\Controllers;

use App\Http\Requests\order\OrderDestroyRequest;
use App\Http\Requests\order\OrderIndexRequest;
use App\Http\Requests\order\OrderStoreRequest;
use App\Http\Requests\order\OrderUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PushNotification;
use App\Models\PushNotificationEvent;
use App\Models\Stock;
use App\Models\Ticket;
use App\Models\User;
use App\Traits\Responser;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(OrderIndexRequest $request)
    {

        $modelQuery = Order::orderBy('id', 'desc')
            ->whenUserId($request->user_id)
            ->whenPaymentTypeId($request->payment_type_id)->whenId($request->id);
        if (!$request->page) return $this->successResponse(OrderResource::collection($modelQuery->get()));

        $models = $modelQuery->paginate($request->limit ?? 5);
        $data = [
            'total' => (int) $models->total(),
            'per_page' => (int)$models->perPage(),
            'current_page' => (int)$models->currentPage(),
            'last_page' => (int) $models->lastPage(),
            'next_page_url' => $models->nextPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'prev_page_url' => $models->previousPageUrl(),
            'orders' =>  OrderResource::collection($models->items()),
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
    public function store(OrderStoreRequest $request)
    {

        $user = User::find($request->user_id);
        $stocksIds = collect($request->stocks)->map(function ($stock, $key) {
            return $stock["id"];
        });
        $stocks = Stock::whereIn('id',  $stocksIds)->get();
        $tickets =  $request->tickets;


        $mappedStock =  collect($request->stocks)->map(function ($item, $key) use ($stocks) {
            $model = $stocks->find($item["id"]);
            return ["model" => $model, "amount" => $item["amount"]];
        });
        $mappedStock = collect($mappedStock);

        $data = array_merge(
            Order::calculateTotal($mappedStock, $tickets),
            Order::addressToArray($request->address_id),
            Order::addressToArray($request->billing_address_id, 'billing_address_'),
            Order::userToArray($request->user_id),
            Order::paymentTypeToArray($request->payment_type_id),
        );


        if (Order::isTicketMaximumLimitExceeded($data["total"], $tickets)) return $this->errorResponse([], "Los Ticket deben ser menor al 95% de total", 422);
        if ($user->isFirstTimeBuyerTicketMaximumLimitExceeded($data["total"], $tickets)) return $this->errorResponse([], "Los Ticket deben ser menor al 95% de total", 422);

        $ticketModel = $user->addTickets($tickets);
        $createdModel = Order::create($data);
        $createdModel->addDetails($stocks, collect($request->stocks));
        $createdModel->addTickets($ticketModel);
        $createdModel->addStatusLog(OrderStatus::find(1));
        $sendNotificationResponse = $createdModel->sendNotification();
        $createdModel->sendOrderCreatedMail([]);

        $createdModel = Order::find($createdModel->id);

        return $this->successResponse(new OrderResource($createdModel), "OK", 200, $sendNotificationResponse);
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
    public function update(OrderUpdateRequest $request)
    {

        $createdModel = Order::find($request->id);

        $sendNotificationResponse = [];
        if ($request->has('numero_guia'))   $createdModel->numero_guia = $request->numero_guia;
        if ($request->has('numero_guia'))   $createdModel->save();
        if ($request->has('numero_guia'))   $createdModel->addStatusLog(OrderStatus::find(OrderStatus::$EN_ENVIO));
        if ($request->has('numero_guia'))  $sendNotificationResponse = $createdModel->sendStatusChangedNotification();
        if ($request->has('numero_guia'))   $createdModel->save();
        if ($request->has('numero_guia'))  return $this->successResponse(new OrderResource($createdModel), "OK", 200, $sendNotificationResponse);



        $orderStatus = OrderStatus::find($request->order_status_id);
        $createdModel->addStatusLog($orderStatus);
        if ($orderStatus->isListo())  $createdModel->addEarningsToUser();
        $sendNotificationResponse =  $createdModel->sendStatusChangedNotification();
        $createdModel->sendOrderStatusChangedMail([]);
        return $this->successResponse(new OrderResource($createdModel), "OK", 200, $sendNotificationResponse);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(OrderDestroyRequest $request)
    {
        $createdModel = Order::find($request->id);
        Order::destroy($request->id);
        return $this->successResponse(new OrderResource($createdModel));
    }
}
