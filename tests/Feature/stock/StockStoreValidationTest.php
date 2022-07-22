<?php

namespace Tests\Feature\stock;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StockStoreValidationTest extends TestCase
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

        $body = Stock::factory()->make(array(
            'price' => "sss",
            'mock_price' => "sss",
            'credits'  => "sss",
            'discount'   => "ssss",
        ))->toArray();
        $body['cover_image_id'] = 44;
        $body['color_id'] = 44;
        $body['size_id'] = 44;
        $body['ribbon_id'] = 44;
        $response = $this->postJson(route('api.mobile.stocks.store'), $body, $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('stocks', 0);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('price')
                            ->has('mock_price')
                            ->has('credits')
                            ->has('discount')


                            ->has('cover_image')
                            ->has('images')
                            ->has('color_id')
                            ->has('size_id')
                            ->has('ribbon_id')
                    )
            );


        $body = array();
        $response = $this->postJson(route('api.mobile.stocks.store'), $body, $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('stocks', 0);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('price')
                            ->has('mock_price')
                            ->has('credits')
                            ->has('discount')
                            ->has('description')
                            ->has('name')
                            ->has('cover_image')
                            ->has('images')


                    )
            );
    }
}
