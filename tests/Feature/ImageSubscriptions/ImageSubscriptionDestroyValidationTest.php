<?php

namespace Tests\Feature\ImageSubscriptions;

use App\Models\Image;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ImageSubscriptionDestroyValidationTest extends TestCase
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
        $endModel = Image::factory()->create();
        $createdModel->images()->attach($endModel->id);
        $this->assertDatabaseCount('image_subscriptions', 1);
        $response = $this->deleteJson(route('api.mobile.stock.imagesubscription.destroy'), array(
            'stock_id' => 44,
            'images_id' => 44
        ), $dataheaders);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('image_subscriptions', 1);
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

        $response = $this->deleteJson(route('api.mobile.stock.imagesubscription.destroy'), array(), $dataheaders);

        $response->assertUnprocessable();
        $this->assertDatabaseCount('image_subscriptions', 1);
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
