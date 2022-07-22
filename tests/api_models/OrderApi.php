<?php

namespace Tests\api_models;

use App\Models\address;
use App\Models\ChatRoom;
use App\Models\Order;
use App\Models\OrderStatus;
use Illuminate\Testing\Fluent\AssertableJson;

class OrderApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.order.store',
            "mainModelName" => Order::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => OrderApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.order.index',
            "mainModelName" => Order::class,
            "mainApiMethod" => 'get',
            "mainApiModelName" => OrderApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.order.update',
            "mainModelName" => Order::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => OrderApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.order.destroy',
            "mainModelName" => Order::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => OrderApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {

        if ($mainApiName == 'api.mobile.order.destroy' || $mainApiName == 'api.mobile.order.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->has('address')
                                ->has('billing_address')
                                ->has('user')
                                ->has('status')
                                ->has('status_log')
                                ->has('details')
                                ->has('payment_type')
                                ->has('tickets')
                                ->has('total')
                                ->has('payed')

                        )
                );



        if ($mainApiName == 'api.mobile.order.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            $controlData['data_length'],
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->has('address')
                                ->has('billing_address')
                                ->has('user')
                                ->has('status')
                                ->has('status_log')
                                ->has('details')
                                ->has('payment_type')
                                ->has('tickets')
                                ->has('total')
                                ->has('payed')
                        )
                );
        if ($mainApiName == 'api.mobile.order.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->has('address')
                                ->has('billing_address')
                                ->has('user')
                                ->has('status')
                                ->has('status_log')
                                ->has('details')
                                ->has('payment_type')
                                ->has('tickets')
                                ->has('total')
                                ->has('payed')
                        )
                );
        return false;
    }
}
