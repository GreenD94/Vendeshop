<?php

namespace Tests\api_models;

use App\Models\address;
use App\Models\ChatRoom;
use App\Models\OrderStatus;
use App\Models\PaymentType;
use Illuminate\Testing\Fluent\AssertableJson;

class PaymentTypeApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.payment_type.store',
            "mainModelName" => PaymentType::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => PaymentTypeApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.payment_type.index',
            "mainModelName" => PaymentType::class,
            "mainApiMethod" => 'get',
            "mainApiModelName" => PaymentTypeApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.payment_type.update',
            "mainModelName" => PaymentType::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => PaymentTypeApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.payment_type.destroy',
            "mainModelName" => PaymentType::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => PaymentTypeApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {
        if ($mainApiName == 'api.mobile.payment_type.destroy' || $mainApiName == 'api.mobile.payment_type.update')
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



        if ($mainApiName == 'api.mobile.payment_type.index')
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
        if ($mainApiName == 'api.mobile.payment_type.store')
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
