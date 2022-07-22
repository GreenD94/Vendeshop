<?php

namespace Tests\Feature\boots;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Stock;
use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RefreshDatabaseTest extends TestCase
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
        $this->checkindex();
    }

    public function checkData(): void
    {
        Sanctum::actingAs(
            User::factory()->create(),
            ['*']
        );


        $this->assertDatabaseCount('stocks',  0);
        $this->assertDatabaseCount('images', 0);
        $this->assertDatabaseCount('videos',  0);
        $this->assertDatabaseCount('categories', 0);
        $this->assertDatabaseCount('colors',  0);
        $this->assertDatabaseCount('sizes',  0);
        $this->assertDatabaseCount('products',  0);
        $this->assertDatabaseCount('banners', 0);
    }

    public function checkindex(): void
    {
        $response = $this->getJson(route('api.mobile.boots.refresh'));

        $response->assertStatus(200);
        $this->assertDatabaseCount('users', 5);
        $this->assertDatabaseCount('stocks',  23);
        $this->assertDatabaseCount('images',  105);
        $this->assertDatabaseCount('videos',  8);
        $this->assertDatabaseCount('categories', 4);
        $this->assertDatabaseCount('colors',   22);
        $this->assertDatabaseCount('sizes', 4);
        $this->assertDatabaseCount('products',  16);
        $this->assertDatabaseCount('banners',  12);

        $this->checkStockindex(null, 23);
        $this->checkStockPagination(1, null, 5);
    }

    public function checkStockindex($params = [], $expected_size = 20): TestResponse
    {
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertStatus(200);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        $expected_size,
                        fn ($json) =>
                        $json
                            ->has('id')
                            ->has('price')
                            ->has('is_available')
                            ->has('is_favorite')
                            ->has('mock_price')
                            ->has('credits')
                            ->has('discount')
                            ->has('categories')
                            ->has('cover_image')
                            ->has('images', 4, fn ($json) =>
                            $json->has('id')
                                ->has('url')
                                ->has('name'))
                            ->has(
                                'videos',
                                1,
                                fn ($json) =>
                                $json->has('id')
                                    ->has('url')
                                    ->has('name')
                                    ->has('is_information')
                            )
                            ->has('description')
                            ->has('name')
                            ->has('color')
                            ->has('colors')
                            // ->has('size')
                            ->has('ribbon')
                            ->has('sizes')
                    )
            );
        return  $response;
    }
    public function checkStockPagination(string $page = "1",  $params = [], $expected_size = 5): TestResponse
    {
        $params["page"] = $page;
        $response = $this->getJson(route('api.mobile.stocks.index', $params));
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('total')
                            ->where('total', 23)
                            ->has('per_page')
                            ->has('current_page')
                            ->has('last_page')
                            ->has('next_page_url')
                            ->has('prev_page_url')
                            ->has('stocks', $expected_size, fn ($json) =>
                            $json
                                ->has('id')
                                ->has('price')
                                ->has('is_available')
                                ->has('is_favorite')
                                ->has('mock_price')
                                ->has('credits')
                                ->has('discount')
                                ->has('categories')
                                ->has('cover_image')
                                ->has('images', 4, fn ($json) =>
                                $json->has('id')
                                    ->has('url')
                                    ->has('name'))
                                ->has('videos', 1, fn ($json) =>
                                $json->has('id')
                                    ->has('url')
                                    ->has('name')
                                    ->has('is_information'))
                                ->has('description')
                                ->has('name')
                                ->has('ribbon')
                                ->has('color')
                                ->has('colors')
                                //  ->has('size')
                                ->has('sizes'))
                    )
            );
        return  $response;
    }
}
