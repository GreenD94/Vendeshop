<?php

namespace Tests\api_models;

use App\Models\address;
use App\Models\ChatRoom;
use App\Models\OrderStatus;
use App\Models\color;
use App\Models\Comercial;
use Illuminate\Testing\Fluent\AssertableJson;

class ComercialsApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.comercials.store',
            "mainModelName" => Comercial::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => ComercialsApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.comercials.index',
            "mainModelName" => Comercial::class,
            "mainApiMethod" => 'get',
            "mainApiModelName" => ComercialsApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.comercials.update',
            "mainModelName" => Comercial::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => ComercialsApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.comercials.destroy',
            "mainModelName" => Comercial::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => ComercialsApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {
        if ($mainApiName == 'api.mobile.comercials.destroy' || $mainApiName == 'api.mobile.comercials.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->has('name')
                                ->has('image', fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('name')
                                    ->has('url'))
                        )
                );



        if ($mainApiName == 'api.mobile.comercials.index')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            $controlData['data_length'],
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->has('name')
                                ->has('image', fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('name')
                                    ->has('url'))
                        )
                );
        if ($mainApiName == 'api.mobile.comercials.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->has('name')
                                ->has('image', fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('name')
                                    ->has('url'))
                        )
                );
        return false;
    }
}
