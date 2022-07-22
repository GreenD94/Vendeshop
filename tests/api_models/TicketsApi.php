<?php

namespace Tests\api_models;

use App\Models\address;
use App\Models\ChatRoom;
use App\Models\OrderStatus;
use App\Models\color;
use App\Models\Ticket;
use Illuminate\Testing\Fluent\AssertableJson;

class TicketsApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.tickets.store',
            "mainModelName" => Ticket::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => TicketsApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.tickets.index',
            "mainModelName" => Ticket::class,
            "mainApiMethod" => 'get',
            "mainApiModelName" => TicketsApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.tickets.update',
            "mainModelName" => Ticket::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => TicketsApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.tickets.destroy',
            "mainModelName" => Ticket::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => TicketsApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {
        if ($mainApiName == 'api.mobile.tickets.destroy' || $mainApiName == 'api.mobile.tickets.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('value', $controlData["value"])
                                ->where('user_id', $controlData["user_id"])
                                ->where('is_used', $controlData["is_used"])
                                ->where('is_active', $controlData["is_active"])
                                ->has('expiration_time')


                        )
                );



        if ($mainApiName == 'api.mobile.tickets.index')
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
                                ->where('value', $controlData["value"])
                                ->where('user_id', $controlData["user_id"])
                                ->where('is_used', $controlData["is_used"])
                                ->where('is_active', $controlData["is_active"])
                                ->has('expiration_time')
                        )
                );
        if ($mainApiName == 'api.mobile.tickets.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('value', $controlData["value"])
                                ->where('user_id', $controlData["user_id"])
                                ->where('is_used', $controlData["is_used"])
                                ->where('is_active', $controlData["is_active"])
                                ->has('expiration_time')
                        )
                );
        return false;
    }
}
