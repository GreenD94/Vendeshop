<?php

namespace Tests\Feature\stock;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StockDestroyValidationTest extends TestCase
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
        $AuthdUser = User::factory()->create();
        Sanctum::actingAs(
            $AuthdUser,
            ['*']
        );
        $dataheaders['Authorization'] = 'Bearer ' . $AuthdUser->createToken($AuthdUser->id . $AuthdUser->name . uniqid())->plainTextToken;

        $createdModel = Stock::factory()->create();
        $this->assertModelExists($createdModel);
        $this->assertDatabaseCount('stocks', 1);
        $this->assertDatabaseCount('colors', 1);
        $this->assertDatabaseCount('images', 2);
        $this->assertDatabaseCount('sizes', 1);
        $response = $this->deleteJson(route('api.mobile.stocks.destroy'), array('id' => 44), $dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );


        $invalidBody = array();
        $response = $this->deleteJson(route('api.mobile.stocks.destroy'), $invalidBody, $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('stocks', 1);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );




        $invalidBody = array(
            'id' => "wssss1",
        );;
        $response = $this->deleteJson(route('api.mobile.stocks.destroy'), $invalidBody, $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('stocks', 1);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );
    }
}
