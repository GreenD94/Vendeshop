<?php

namespace Tests\api_models;

use App\Models\ChatRoom;
use Illuminate\Testing\Fluent\AssertableJson;

class ChatRoomsApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.chat_rooms.store',
            "mainModelName" => ChatRoom::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => ChatRoomsApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.chat_rooms.index',
            "mainModelName" => ChatRoom::class,
            "mainApiMethod" => 'get',
            "mainApiModelName" => ChatRoomsApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.chat_rooms.update',
            "mainModelName" => ChatRoom::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => ChatRoomsApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.chat_rooms.destroy',
            "mainModelName" => ChatRoom::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => ChatRoomsApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {
        if ($mainApiName == 'api.mobile.chat_rooms.destroy' || $mainApiName == 'api.mobile.chat_rooms.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('is_active', $controlData["is_active"])

                        )
                );



        if ($mainApiName == 'api.mobile.chat_rooms.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',

                            fn ($json) =>
                            $json->has('total')
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('chat_rooms',  $controlData['data_length'], fn ($json) =>
                                $json
                                    ->where('id', $controlData["id"])
                                    ->where('is_active', $controlData["is_active"]))
                        )
                );
        if ($mainApiName == 'api.mobile.chat_rooms.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')

                                ->where('is_active', $controlData["is_active"])
                        )
                );
        return false;
    }
}
