<?php

namespace Tests\Feature\stock;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StockIndexValidationTest extends TestCase
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
        $this->checkPageIsIntenger();
        $this->checkPageIsNumeric();
        $this->checkPageIsNaturalNumber();
        $this->checkOrderByIsUnvalid();
        $this->checkLimitIsIntenger();
        $this->checkLimitIsNumeric();
        $this->checkLimitIsNaturalNumber();

        $this->checkCategoryIdExists();
        $this->checkCategoryIdIsIntenger();
        $this->checkCategoryIdIsNumeric();



        $this->checkIsFavoriteBoolean();
        $this->checkIsFavoriteByUserIdExists();
        $this->checkIsFavoriteByUserIdIntenger();
        $this->checkIsFavoriteByUserIdNumeric();
    }
    public function checkData()
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );
    }
    public function checkPageIsIntenger()
    {
        $params = array("page" => 1.1);
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('page')
                    )
            );
    }
    public function checkPageIsNumeric()
    {
        $params = array("page" => "aaa");
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('page')
                    )
            );
    }
    public function checkPageIsNaturalNumber()
    {
        $params = array("page" => -1);
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('page')
                    )
            );
    }
    public function checkOrderByIsUnvalid()
    {
        $params = array("order_by" => "asddaaa");
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('order_by')
                    )
            );
    }
    public function checkLimitIsIntenger()
    {
        $params = array("limit" => 1.1);
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('limit')
                    )
            );
    }
    public function checkLimitIsNumeric()
    {
        $params = array("limit" => "aaa");
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('limit')
                    )
            );
    }
    public function checkLimitIsNaturalNumber()
    {
        $params = array("limit" => -1);
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('limit')
                    )
            );
    }



    public function checkCategoryIdExists()
    {
        $params = array("category_id" => 22);
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('category_id')->etc()
                    )
            );
    }
    public function checkCategoryIdIsIntenger()
    {
        $params = array("category_id" => 1.1);
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('category_id')->etc()
                    )
            );
    }
    public function checkCategoryIdIsNumeric()
    {
        $params = array("category_id" => "aaa");
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('category_id')->etc()
                    )
            );
    }



    public function checkIsFavoriteBoolean()
    {
        $params = array(
            'is_favorite' =>   "aaaa",
        );
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('is_favorite')
                    )
            );
    }
    public function checkIsFavoriteByUserIdExists()
    {
        $params = array(
            'is_favorite' =>   '#22',
        );
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('is_favorite')
                    )
            );
    }
    public function checkIsFavoriteByUserIdIntenger()
    {
        $params = array(
            'is_favorite' => '#1.1'
        );
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('is_favorite')
                    )
            );
    }
    public function checkIsFavoriteByUserIdNumeric()
    {
        $params = array(
            'is_favorite' => "#ssss"
        );
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('is_favorite')
                    )
            );
    }
}
