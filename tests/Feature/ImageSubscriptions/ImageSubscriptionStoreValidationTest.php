<?php

namespace Tests\Feature\ImageSubscriptions;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ImageSubscriptionStoreValidationTest extends TestCase
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

        $response = $this->postJson(route('api.mobile.stock.imagesubscription.store'), array(
            'stock_id' => 44,
            'image_id' => 44
        ), $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('image_subscriptions', 0);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('stock_id')
                            ->has('image_id')
                    )
            );
        $response = $this->postJson(route('api.mobile.stock.imagesubscription.store'), array(), $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('image_subscriptions', 0);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('stock_id')
                            ->has('image_id')
                    )
            );
    }
}
