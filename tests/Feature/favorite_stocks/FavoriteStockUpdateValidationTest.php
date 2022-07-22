<?php

namespace Tests\Feature\favorite_stocks;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FavoriteStockUpdateValidationTest extends TestCase
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

        $this->checkStockIdRequired();
        $this->checkStockIdExists();
        $this->checkStockIdIsIntenger();
        $this->checkStockIdIsNumeric();

        $this->checkUserIdExists();
        $this->checkUserIdIsIntenger();
        $this->checkUserIdIsNumeric();
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

    public function checkUserIdExists()
    {
        $params = array(
            'stock_id' =>   $this->endModel->id,
            'user_id' => 22
        );
        $response = $this->putJson(route('api.mobile.stock.favorite.update'), $params,  $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('user_id')
                    )
            );
    }
    public function checkUserIdIsIntenger()
    {
        $params = array(
            'stock_id' =>   $this->endModel->id,
            'user_id' => 1.1
        );
        $response = $this->putJson(route('api.mobile.stock.favorite.update'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('user_id')
                    )
            );
    }
    public function checkUserIdIsNumeric()
    {
        $params = array(
            'stock_id' =>   $this->endModel->id,
            'user_id' => "ssss"
        );
        $response = $this->putJson(route('api.mobile.stock.favorite.update'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('user_id')
                    )
            );
    }



    public function checkStockIdRequired()
    {
        $params = array(
            'user_id' => $this->createdModel->id
        );
        $response = $this->putJson(route('api.mobile.stock.favorite.update'), $params, $this->dataheaders);
        $response->assertUnprocessable();

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('stock_id')
                    )
            );
    }
    public function checkStockIdExists()
    {
        $params = array(
            'stock_id' =>   22,
            'user_id' => $this->createdModel->id
        );
        $response = $this->putJson(route('api.mobile.stock.favorite.update'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('stock_id')
                    )
            );
    }
    public function checkStockIdIsIntenger()
    {
        $params = array(
            'stock_id' =>   21.1,
            'user_id' => $this->createdModel->id
        );
        $response = $this->putJson(route('api.mobile.stock.favorite.update'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('stock_id')
                    )
            );
    }
    public function checkStockIdIsNumeric()
    {
        $params = array(
            'stock_id' =>  "aaa",
            'user_id' => $this->createdModel->id
        );
        $response = $this->putJson(route('api.mobile.stock.favorite.update'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('stock_id')
                    )
            );
    }
}
