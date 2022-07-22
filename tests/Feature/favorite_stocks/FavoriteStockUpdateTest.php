<?php

namespace Tests\Feature\favorite_stocks;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FavoriteStockUpdateTest extends TestCase
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
        $this->checkFavoriteTrueUpdate();
        $this->checkFavoriteFalseUpdate();
    }
    public function checkData()
    {
        $authUser = User::factory()->create();
        Sanctum::actingAs(
            $authUser,
            ['*']
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $authUser->createToken($authUser->id . $authUser->name . uniqid())->plainTextToken;

        $this->createdModel = User::factory()->create();
        $this->endModel = Stock::factory()->create();
    }
    public function checkFavoriteTrueUpdate()
    {

        $response = $this->putJson(route('api.mobile.stock.favorite.update'), array(
            'stock_id' =>   $this->endModel->id,
            'user_id' => $this->createdModel->id
        ), $this->dataheaders);
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->where('is_favorite', true)
                    )
            );


        $this->assertDatabaseCount('favorite_stocks', 1);
        $this->assertDatabaseHas('favorite_stocks', [
            'stock_id' =>   $this->endModel->id,
            'user_id' => $this->createdModel->id
        ]);
    }
    public function checkFavoriteFalseUpdate()
    {

        $response = $this->putJson(route('api.mobile.stock.favorite.update'), array(
            'stock_id' =>   $this->endModel->id,
            'user_id' => $this->createdModel->id
        ), $this->dataheaders);
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->where('is_favorite', false)
                    )
            );


        $this->assertDatabaseCount('favorite_stocks', 0);
    }
    public function checkFavoriteByTokenTrueUpdate()
    {

        $response = $this->putJson(route('api.mobile.stock.favorite.update'), array(
            'stock_id' =>   $this->endModel->id,
        ), $this->dataheaders);
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->where('is_favorite', true)
                    )
            );


        $this->assertDatabaseCount('favorite_stocks', 1);
        $this->assertDatabaseHas('favorite_stocks', [
            'stock_id' =>   $this->endModel->id,
            'user_id' => $this->createdModel->id
        ]);
    }
    public function checkFavoriteByTokenFalseUpdate()
    {

        $response = $this->putJson(route('api.mobile.stock.favorite.update'), array(
            'stock_id' =>   $this->endModel->id,
        ), $this->dataheaders);
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->where('is_favorite', false)
                    )
            );


        $this->assertDatabaseCount('favorite_stocks', 0);
    }
}
