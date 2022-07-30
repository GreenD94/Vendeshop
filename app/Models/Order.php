<?php

namespace App\Models;

use App\Http\Resources\OrderResource;
use App\Http\Resources\PushNotificationEventResource;
use App\Http\Resources\PushNotificationResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection as SupportCollection;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'user_first_name',
        'user_last_name',
        'user_phone',
        'user_email',
        'user_birth_date',
        'user_email_verified_at',
        'user_avatar_id',
        'user_avatar_url',

        'address_id',
        'address_address',
        'address_city_id',
        'address_city_name',
        'address_street',
        'address_postal_code',
        'address_deparment',
        'address_phone_number',
        'address_state_name',
        'address_state_id',
        'billing_address_id',
        'billing_address_address',
        'billing_address_city_id',
        'billing_address_city_name',
        'billing_address_street',
        'billing_address_postal_code',
        'billing_address_deparment',
        'billing_address_phone_number',
        'billing_address_state_id',
        'billing_address_state_name',
        'payment_type_id',
        'payment_type_name',
        'total',
        'numero_guia',
        'shipping_cost'

    ];
    protected $appends = ['status'];





    public static  function isTicketMaximumLimitExceeded($total, $ticket)
    {
        $limit = $total * (0.95);
        $isExceeded = $ticket >= $limit;
        return  $isExceeded;
    }

    public  function addEarningsToUser()
    {
        $ticketConfig = TicketConfig::where('is_active', true)->first();
        if (!$ticketConfig) return;
        $value = 0;
        if ($ticketConfig->return_percentage > 0) $value = $ticketConfig->return_percentage * $this->total;
        if ($ticketConfig->return_price > 0) $value = $ticketConfig->return_price;

        $user = User::find($this->user_id);
        $user->tickets()->create(
            [

                'value' => $value,
                'expiration_time' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }

    public static function calculateTotal($stocks,  $tickets)
    {
        $stocks->transform(function ($value, $key) {
            $stock = $value["model"];
            $amount = (int) $value["amount"];
            $discount = (($stock->mock_price - $stock->price) / $stock->mock_price);
            $stock->price = $stock->price * $amount;
            return $stock;
        });

        $stocksTotal = $stocks->sum('price');
        $ticketsTotal = $tickets;
        $shippingcost =  Order::shippingCostToArray($stocksTotal);

        $total = $stocksTotal - $ticketsTotal + $shippingcost['shipping_cost'];

        return ['total' => $total, 'shipping_cost' => $shippingcost['shipping_cost']];
    }


    public static function shippingCostToArray($stocksTotal): array
    {
        $shippingCostModel = ShippingCost::where('is_active', true)->first();
        if (!$shippingCostModel) return ['shipping_cost' => 0];
        $shippingcost = 0;
        $shippingcostPrice =      $shippingCostModel?->price ?? 0;
        $shippingcostPercentage =      $shippingCostModel?->price_percentage ?? 0;
        if ($shippingcostPrice > 0) $shippingcost = $shippingcostPrice;
        if ($shippingcostPercentage > 0) $shippingcost = $stocksTotal * $shippingcostPercentage;

        $array = [
            'shipping_cost' =>  $shippingcost
        ];
        return $array;
    }
    public static function addressToArray($address_id, string $prefix = "address_"): array
    {
        if (!$address_id) return [];
        $address = address::find($address_id);
        $array = [
            $prefix . 'id' => $address->id,
            $prefix . 'address' => $address->address,
            $prefix . 'city_id' => $address->city_id,
            $prefix . 'city_name' => $address->city_name,
            $prefix . 'street' => $address->street,
            $prefix . 'postal_code' => $address->postal_code,
            $prefix . 'deparment' => $address->deparment,
            $prefix . 'phone_number' => $address->phone_number,
            $prefix . 'state_name' => $address->state_name,
            $prefix . 'state_id' => $address->state_id,
        ];
        return $array;
    }

    public static function userToArray($user_id, string $prefix = "user_"): array
    {
        if (!$user_id) return [];
        $user = User::find($user_id);
        $array = [
            $prefix . 'id' => $user->id,
            $prefix . 'first_name' => $user->first_name,
            $prefix . 'last_name' => $user->last_name,
            $prefix . 'phone' => $user->phone,
            $prefix . 'email' => $user->email,
            $prefix . 'birth_date' => $user->birth_date,
            $prefix . 'email_verified_at' => $user->email_verified_at,
            $prefix . 'avatar_id' => $user?->avatar_id,
            $prefix . 'avatar_url' => $user?->avatar?->url,
        ];
        return $array;
    }

    public static function paymentTypeToArray(int $payment_type_id, string $prefix = "payment_type_"): array
    {
        if (!$payment_type_id) return [];
        $payment_type = PaymentType::find($payment_type_id);
        $array = [
            $prefix . 'id' => $payment_type->id,
            $prefix . 'name' => $payment_type->name,
        ];
        return $array;
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }



    public function status_Logs()
    {
        return $this->hasMany(OrderStatusLog::class,  'order_id', 'id');
    }

    public function getStatusAttribute()
    {
        return $this->status_logs->last();
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class,  'order_id', 'id');
    }
    public function tickets()
    {
        return $this->hasMany(OrderTicket::class,  'order_id', 'id');
    }

    public function scopeWhenUserId($query, $user_id)
    {
        if ($user_id) $query->where('user_id', $user_id);
    }

    public function scopeWhenPaymentTypeId($query, $payment_type_id)
    {
        if ($payment_type_id) $query->where('payment_type_id', $payment_type_id);
    }

    public function scopeWhenId($query, $id)
    {
        if ($id)
            $query->where('id', $id);
    }

    public function addDetails(Collection $stocks, SupportCollection  $stockData)
    {
        $createdModel = $this;
        $stocks->each(function ($stock, $key) use ($createdModel, $stockData) {
            $firstStockData = $stockData->firstWhere('id', $stock->id);

            $quantity = $firstStockData["amount"] ?? 1;
            $color_id = $firstStockData["color_id"] ?? null;
            $color_hex = ($color_id) ? Color::find($color_id)->hex : null;
            $size_id = $firstStockData["size_id"] ?? null;
            $size_size = ($size_id) ? Size::find($size_id)->size : null;


            $createdModel->details()->create(
                [
                    'discount' => 0,
                    'price' => $stock->price,
                    'mock_price' => $stock->mock_price,
                    'credits' => $stock->credits,
                    'quantity' => $quantity,
                    'cover_image_id' => $stock?->cover_image_id,
                    'cover_image_url' => $stock?->cover_image?->url,
                    'description' => $stock->description,
                    'name' => $stock->description,
                    'color_id' => $color_id,
                    'color_hex' => $color_hex,
                    'size_id' => $size_id,
                    'size_size' => $size_size,
                    'stock_id' => $stock->id
                ]
            );
        });
    }

    public function scopeWhenStatusId($query, $status_id)
    {
        if ($status_id) $query->whereHas('status_Logs', function (Builder $query) use ($status_id) {
            $query->where('order_status_id', $status_id);
        });
    }


    public function scopeReportByStatus($query)
    {
        $orderStatuses = OrderStatus::get();
        return $orderStatuses->map(function ($status) use ($query) {
            $mappedOrders = collect([]);
            $orders =  (clone $query)->WhenStatusId($status->id)->pluck('total', 'id');
            $orders = $orders->each(function ($item, $key) use (&$mappedOrders) {
                $mappedOrders->push(["id" => $key, "value" => $item]);
            });


            return [
                "status_id" => $status->id,
                "status_name" => $status->name,
                "total_orders" => (clone $query)->WhenStatusId($status->id)->count(),
                "total_orders_price" => (clone $query)->WhenStatusId($status->id)->sum('total'),
                "orders" => $mappedOrders->toArray()
            ];
        });
    }



    public function addTickets($ticket): void
    {

        if (!$ticket || $ticket?->value >= 0) {
            return;
        };
        $createdModel = $this;
        $createdModel->tickets()->create(
            [
                'ticket_id' => $ticket->id,
                'value' => $ticket->value,
                'expiration_time' => $ticket->expiration_time,
            ]
        );
    }

    public function addStatusLog(OrderStatus $status)
    {
        $createdModel = $this;
        $createdModel->status_Logs()->create(
            [
                'order_status_id' =>  $status->id,
                'order_status_name' => $status->name,


            ]
        );
    }

    public function sendNotification($is_alive = true)
    {

        $push_notification_event =  PushNotificationEvent::find(PushNotificationEvent::$NEW_ORDER);
        $usersId = User::role('admin')->pluck('id')->all();
        $maserId = User::role('master')->pluck('id')->all();
        $usersId = array_merge($usersId, $maserId);
        $modelData = [
            "user_id" =>  $usersId,
            "tittle" => "new order has been created",
            "body" =>  [
                "order" => new OrderResource($this),
                "event" => new PushNotificationEventResource($push_notification_event)
            ],
            "is_live" => $is_alive,
            "push_notification_event_id" => $push_notification_event->id,
        ];

        $finalData = [];
        if ($is_alive) {
            $tittle2 = 'ORDEN N°: ' . $this->id;
            $result = PushNotification::sendPushNotification($usersId, "Nueva Orden",  $modelData['body'], PushNotification::$ADMIN_PROJECT_ID, PushNotification::getGoogleAccessAdminToken(), $tittle2);
            $result = PushNotification::sendPushNotification($usersId, "Nueva Orden",  $modelData['body'], PushNotification::$USER_PROJECT_ID, PushNotification::getGoogleAccessUserToken(), $tittle2);
            if ($result["code"] != 200)  $finalData[] = $result["data"];
        }
        $modelData['body'] = json_encode($modelData['body']);
        $data = [];
        foreach ($usersId as $key => $id) {
            $modelData['user_id'] = $id;
            array_push($data, $modelData);
        }
        foreach ($data as $key => $d) {
            PushNotification::create($d);
        }
        return  ['data' => $finalData, "message" => "success", "code" => 200];
    }

    public function sendStatusChangedNotification($is_alive = true)
    {

        $push_notification_event =   PushNotificationEvent::find(PushNotificationEvent::$ORDER_STATE_CHANGE);
        $usersId = User::role('admin')->pluck('id')->all();
        $maserId = User::role('master')->pluck('id')->all();
        $usersId = array_merge($usersId, $maserId);

        $usersId[] = (int) $this->user_id;
        $last_status_logs_index = $this->status_logs->count() - 1;
        $second_last_status_logs_index = $last_status_logs_index - 1;
        $last_status_logs = $this->status_logs[$last_status_logs_index];
        $second_last_status_logs = $this->status_logs[$second_last_status_logs_index];

        $modelData = [
            "user_id" =>  $usersId,
            "tittle" => "order (" . $this->id . ") has changed status from (" . $second_last_status_logs->name . ") to (" . $last_status_logs->name . ")",
            "body" =>  [
                "order" => new OrderResource($this),
                "event" => new PushNotificationEventResource($push_notification_event)
            ],
            "is_live" => $is_alive,
            "push_notification_event_id" => $push_notification_event->id,
        ];


        if ($is_alive) {
            $result = PushNotification::sendPushNotification($usersId, "cambio a estado de " . $last_status_logs->name,  $modelData['body'], PushNotification::$ADMIN_PROJECT_ID, PushNotification::getGoogleAccessAdminToken());
            $result = PushNotification::sendPushNotification($usersId, "cambio a estado de " . $last_status_logs->name,  $modelData['body'], PushNotification::$USER_PROJECT_ID, PushNotification::getGoogleAccessUserToken());
            if ($result["code"] != 200)  return ['data' => $result["data"], "message" => $result["message"], "code" =>  $result["code"]];
        }
        $modelData['body'] = json_encode($modelData['body']);
        $data = [];
        foreach ($usersId as $key => $id) {
            $modelData['user_id'] = $id;
            array_push($data, $modelData);
        }
        foreach ($data as $key => $d) {
            PushNotification::create($d);
        }
        return  ['data' => [], "message" => "success", "code" => 200];
    }

    public function sendOrderCreatedMail($data)
    {
        $adminEmails = User::role('admin')->pluck('email')->all();
        $maserEmails = User::role('master')->pluck('email')->all();
        $mail = array_merge($adminEmails, $maserEmails, [$this->user_email]);

        $data["orden"] = $this;
        Mail::to($mail)->send(new \App\Mail\OrderCreated($data));
    }

    public function sendOrderStatusChangedMail($data)
    {
        $adminEmails = User::role('admin')->pluck('email')->all();
        $maserEmails = User::role('master')->pluck('email')->all();
        $mail = array_merge($adminEmails, $maserEmails, [$this->user_email]);

        $data["orden"] = $this;
        Mail::to($mail)->send(new \App\Mail\OrderStatusChanged($data));
    }
}
