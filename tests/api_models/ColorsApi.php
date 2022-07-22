<?php

namespace Tests\api_models;

use App\Models\address;
use App\Models\ChatRoom;
use App\Models\OrderStatus;
use App\Models\color;

use Illuminate\Testing\Fluent\AssertableJson;

class ColorsApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.colors.store',
            "mainModelName" => Color::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => ColorsApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.colors.index',
            "mainModelName" => Color::class,
            "mainApiMethod" => 'get',
            "mainApiModelName" => ColorsApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.colors.update',
            "mainModelName" => Color::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => ColorsApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.colors.destroy',
            "mainModelName" => Color::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => ColorsApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {
        if ($mainApiName == 'api.mobile.colors.destroy' || $mainApiName == 'api.mobile.colors.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('hex', $controlData["hex"])
                                ->has('image')


                        )
                );



        if ($mainApiName == 'api.mobile.colors.index')
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
                                ->where('hex', $controlData["hex"])
                                ->has('image')
                        )
                );
        if ($mainApiName == 'api.mobile.colors.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('hex', $controlData["hex"])
                                ->has('image')
                        )
                );
        return false;
    }
}
