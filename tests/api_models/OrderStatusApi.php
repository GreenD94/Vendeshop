<?php

namespace Tests\api_models;

use App\Models\address;
use App\Models\ChatRoom;
use App\Models\OrderStatus;
use Illuminate\Testing\Fluent\AssertableJson;

class OrderStatusApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.order_status.store',
            "mainModelName" => OrderStatus::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => OrderStatusApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.order_status.index',
            "mainModelName" => OrderStatus::class,
            "mainApiMethod" => 'get',
            "mainApiModelName" => OrderStatusApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.order_status.update',
            "mainModelName" => OrderStatus::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => OrderStatusApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.order_status.destroy',
            "mainModelName" => OrderStatus::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => OrderStatusApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {
        if ($mainApiName == 'api.mobile.order_status.destroy' || $mainApiName == 'api.mobile.order_status.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('name', $controlData["name"])


                        )
                );



        if ($mainApiName == 'api.mobile.order_status.index')
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
                                ->where('name', $controlData["name"])
                        )
                );
        if ($mainApiName == 'api.mobile.order_status.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('name', $controlData["name"])
                        )
                );
        return false;
    }
}
