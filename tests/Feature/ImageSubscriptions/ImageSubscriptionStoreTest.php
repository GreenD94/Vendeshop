<?php

namespace Tests\Feature\ImageSubscriptions;

use App\Models\Image;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ImageSubscriptionStoreTest extends TestCase
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
        $response = $this->postJson(route('api.mobile.stock.imagesubscription.store'), array(
            'stock_id' => $createdModel->id,
            'image_id' => $endModel->id
        ), $dataheaders);
        $response->assertStatus(200);
        $this->assertDatabaseCount('image_subscriptions', 1);
    }
}
