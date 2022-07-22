<?php

namespace Tests\api_models;

use App\Models\address;
use App\Models\ChatRoom;
use App\Models\OrderStatus;
use App\Models\color;
use App\Models\Size;
use Illuminate\Testing\Fluent\AssertableJson;

class SizesApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.sizes.store',
            "mainModelName" => Size::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => SizesApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.sizes.index',
            "mainModelName" => Size::class,
            "mainApiMethod" => 'get',
            "mainApiModelName" => SizesApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.sizes.update',
            "mainModelName" => Size::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => SizesApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.sizes.destroy',
            "mainModelName" => Size::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => SizesApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {
        if ($mainApiName == 'api.mobile.sizes.destroy' || $mainApiName == 'api.mobile.sizes.update')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->where('id', $controlData["id"])
                                ->where('size', $controlData["size"])
                                ->has('image')


                        )
                );



        if ($mainApiName == 'api.mobile.sizes.index')
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
                                ->where('size', $controlData["size"])
                                ->has('image')
                        )
                );
        if ($mainApiName == 'api.mobile.sizes.store')
            return $response
                ->assertJson(
                    fn (AssertableJson $json) =>
                    $json->has('message')
                        ->has(
                            'data',
                            fn ($json1) =>
                            $json1
                                ->has('id')
                                ->where('size', $controlData["size"])
                                ->has('image')
                        )
                );
        return false;
    }
}
