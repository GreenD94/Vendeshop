<?php

namespace Tests\Feature\boots;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BootIndexValidationTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->checkData();
        $this->checkStockLimitIsIntenger();
        $this->checkStockLimitIsNumeric();
        $this->checkLatestLimitIsIntenger();
        $this->checkLatestLimitIsNumeric();
    }

    public function checkData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }

    public function checkStockLimitIsIntenger()
    {
        $params = array("stock_limit" => 1.1);
        $response = $this->getJson(route('api.mobile.boots.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('stock_limit')
                    )
            );
    }
    public function checkStockLimitIsNumeric()
    {
        $params = array("stock_limit" => "aaa");
        $response = $this->getJson(route('api.mobile.boots.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('stock_limit')
                    )
            );
    }
    public function checkLatestLimitIsIntenger()
    {
        $params = array("Latest_limit" => 1.1);
        $response = $this->getJson(route('api.mobile.boots.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('Latest_limit')
                    )
            );
    }
    public function checkLatestLimitIsNumeric()
    {
        $params = array("Latest_limit" => "aaa");
        $response = $this->getJson(route('api.mobile.boots.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('Latest_limit')
                    )
            );
    }
}
