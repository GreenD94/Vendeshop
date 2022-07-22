<?php

namespace Tests\api_models;

use App\Models\address;
use App\Models\ChatRoom;
use Illuminate\Testing\Fluent\AssertableJson;

class ReportApi
{

    static public function store()
    {
        return [
            "mainApiName" => 'api.mobile.reports.store',
            "mainModelName" => address::class,
            "mainApiMethod" => 'post',
            "mainApiModelName" => AddressApi::class
        ];
    }
    static public function index()
    {
        return [
            "mainApiName" => 'api.mobile.reports.index',
            "mainModelName" => null,
            "mainApiMethod" => 'get',
            "mainApiModelName" => AddressApi::class
        ];
    }
    static public function update()
    {
        return [
            "mainApiName" => 'api.mobile.address.update',
            "mainModelName" => address::class,
            "mainApiMethod" => 'put',
            "mainApiModelName" => AddressApi::class
        ];
    }
    static public function destroy()
    {
        return [
            "mainApiName" => 'api.mobile.address.destroy',
            "mainModelName" => address::class,
            "mainApiMethod" => 'delete',
            "mainApiModelName" => AddressApi::class
        ];
    }
    static public function checkResponse(string $mainApiName, \Illuminate\Testing\TestResponse $response, array $controlData)
    {



        if ($mainApiName == 'api.mobile.address.index')
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
                                ->has('address',  $controlData['data_length'], fn ($json) =>
                                $json
                                    ->where('id', $controlData["id"])
                                    ->where('address', $controlData["address"])
                                    ->where('city_id', $controlData["city_id"])
                                    ->where('street', $controlData["street"])
                                    ->where('postal_code', $controlData["postal_code"])
                                    ->where('deparment', $controlData["deparment"])
                                    ->where('phone_number', $controlData["phone_number"])
                                    ->has('city_name'))

                        )
                );

        return false;
    }
}
