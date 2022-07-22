<?php

namespace Tests\Feature\stock;

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
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use \Illuminate\Testing\TestResponse;

class StockIndexTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;


    /**
     *
     * @return void
     */
    public function test_example()
    {

        $this->checkData();
        // $this->checkindex();
        // $this->checkPagination();
        // $this->checkPaege2Pagination();
        // $this->checkOrderByLast();
        // $this->checkOrderByRandom();
        // $this->checkLimit();
        // $this->checkCategoryId();
        // $this->checkSearch();
        // $this->checkIsFavoriteTrue();
        // //$this->checkIsFavoriteFalse();
        // $this->checkIsFavoriteByUserId();
    }

    public function checkindex(array $params = [], $expected_size = 10): TestResponse
    {
        $response = $this->getJson(route('api.mobile.stocks.index', $params), $this->dataheaders);
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
                            ->has('mock_price')
                            ->has('credits')
                            ->has('discount')
                            ->has('cover_image')
                            ->has('images', 3, fn ($json) =>
                            $json->has('id')
                                ->has('url')
                                ->has('name'))
                            ->has('ribbon', fn ($json) =>
                            $json->has('id')
                                ->has('url'))
                            ->has('videos', 3, fn ($json) =>
                            $json->has('id')
                                ->has('url')
                                ->has('name')
                                ->has('is_information'))
                            ->has('description')
                            ->has('name')
                            ->has('color')
                            ->has('colors')
                            ->has('categories')
                            //   ->has('size')
                            ->has('sizes')
                            ->has('is_favorite')
                            ->has('is_available')

                    )
            );
        return  $response;
    }
    public function checkPagination(string $page = "1", array $params = [], $expected_size = 5, $total = 10): TestResponse
    {
        $params["page"] = $page;
        $response = $this->getJson(route('api.mobile.stocks.index', $params), $this->dataheaders);
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('total')
                            ->where('total', $total)
                            ->has('per_page')
                            ->has('current_page')
                            ->has('last_page')
                            ->has('next_page_url')
                            ->has('prev_page_url')
                            ->has(
                                'stocks',
                                $expected_size,
                                fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('is_available')
                                    ->has('price')
                                    ->has('mock_price')
                                    ->has('credits')
                                    ->has('discount')
                                    ->has('cover_image')
                                    ->has('images', 3, fn ($json) =>
                                    $json->has('id')
                                        ->has('url')
                                        ->has('name'))
                                    ->has('videos', 3, fn ($json) =>
                                    $json->has('id')
                                        ->has('url')
                                        ->has('name')
                                        ->has('is_information'))
                                    ->has('description')
                                    ->has('name')
                                    ->has('ribbon', fn ($json) =>
                                    $json->has('id')
                                        ->has('url'))
                                    ->has('colors')
                                    ->has('color')
                                    ->has('categories')
                                    ->has('sizes')
                                    //   ->has('size')
                                    ->has('is_favorite')
                            )
                    )
            );
        return  $response;
    }
    public function checkPaege2Pagination(): void
    {

        $response = $this->checkPagination(2);
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has("message")
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('total')
                            ->has(
                                'stocks',
                                5,
                                fn ($json) =>
                                $json->where("id", $this->products[5]->id)->etc()
                            )->etc()
                    )
            );
    }

    public function checkData(): void
    {
        $authUser = User::factory()->create();
        Sanctum::actingAs(
            $authUser,
            ['*']
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $authUser->createToken($authUser->id . $authUser->name . uniqid())->plainTextToken;

        $product_factory = Product::factory();
        $stock_radom_total = 7;
        $stock_name_total = 2;
        $category_name_total = 1;
        $this->products = Stock::factory()
            ->has(Image::factory()->count(3), 'images')
            ->has(Video::factory()->count(3), 'videos')
            ->has(Category::factory()->count(3)->state(new Sequence(
                ['is_main' => true],
                ['is_main' => false],
            )), 'categories')
            ->for($product_factory)
            ->count($stock_radom_total)
            ->create();

        $category_name_stock = Stock::factory()
            ->has(Image::factory()->count(3), 'images')
            ->has(Video::factory()->count(3), 'videos')
            ->has(Category::factory()->count(3)->state(new Sequence(
                ['is_main' => true],
                ['is_main' => false],
            )), 'categories')
            ->for($product_factory)
            ->count($category_name_total)
            ->create(["name" => "pedro42"]);


        $authUser->favorite_stock()->attach($category_name_stock[0]->id);

        $stock_name_stock = Stock::factory()
            ->has(Image::factory()->count(3), 'images')
            ->has(Video::factory()->count(3), 'videos')
            ->has(Category::factory()->count(3)->state(["name" => "pedro42"]), 'categories')
            ->for($product_factory)
            ->count($stock_name_total)
            ->create();

        $radomUser = User::factory()->create();

        $radomUser->favorite_stock()->attach($stock_name_stock[0]->id);
        $radomUser->favorite_stock()->attach($stock_name_stock[1]->id);

        $product_total = $stock_radom_total + $stock_name_total + $category_name_total;


        $discount = 0.20;
        //  DB::statement("UPDATE 'stocks' SET 'price' = ('price' * " . $discount . ')');
        Stock::where('id', '>', 0)->update(
            ["price" => DB::raw('("price"-("price" * 0.50))')]
        );
        $stock = Stock::select('mock_price', 'price')->get()->map(function ($item, $key) {
            return [
                'mock_price' => $item->mock_price,
                'discount' => $discount = floatval(number_format(($item->mock_price - $item->price) / $item->mock_price, 2, '.', '')),
                'price' => $item->price,
            ];
        });
        dd($stock);
        return;
        $this->assertDatabaseCount('stocks',  $product_total);
        $this->assertDatabaseCount('images',  $product_total * 8);
        $this->assertDatabaseCount('videos',  $product_total * 3);
        $this->assertDatabaseCount('categories',  $product_total * 3);
        $this->assertDatabaseCount('colors',  10);
        $this->assertDatabaseCount('sizes',  10);
        $this->assertDatabaseCount('products', 3);

        $this->totalStocksWithCategoryID1 = Category::first()->stocks->count();
    }
    public function checkOrderByLast(): void
    {

        $response = $this->checkindex(array("order_by" => "latest"));
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has(
                        'data',
                        10,
                        fn ($json) =>
                        $json->where("id", $this->products[0]->id)->etc()
                    )->etc()
            );

        $response = $this->checkPagination(1, array("order_by" => "latest"));
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('total')
                            ->has(
                                'stocks',
                                5,
                                fn ($json) =>
                                $json->where("id", $this->products[0]->id)->etc()
                            )->etc()
                    )->etc()
            );
    }
    public function checkOrderByRandom()
    {
        $response = $this->checkindex(array("order_by" => "random"));
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has(
                        'data'
                    )->etc()
            );
        $this->assertTrue($response->original["data"][0]->id != $this->products[0]->id);


        $response = $this->checkPagination(1, array("order_by" => "random"));
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('total')
                            ->has(
                                'stocks',
                                5
                            )->etc()
                    )->etc()
            );
        $this->assertTrue($response->original["data"]["stocks"][0]->id != $this->products[0]->id);
    }
    public function checkLimit()
    {
        $response = $this->checkindex(array("limit" => 3), 3);


        $response = $this->checkPagination(1, array("limit" => 3), 3);
    }
    public function checkCategoryId()
    {
        $response = $this->checkindex(array("category_id" => Category::first()->id), $this->totalStocksWithCategoryID1);


        $response = $this->checkPagination(1, array("category_id" => Category::first()->id), $this->totalStocksWithCategoryID1, $this->totalStocksWithCategoryID1);
    }


    public function checkSearch()
    {
        $response = $this->checkindex(array("search" => "pedro42"), 3);

        $response = $this->checkPagination(1, array("search" => "pedro42"), 3, 3);
    }

    public function checkIsFavoriteTrue()
    {
        $response = $this->checkindex(array("is_favorite" => true), 1);

        $response = $this->checkPagination(1, array("is_favorite" => true), 1, 1);
    }

    public function checkIsFavoriteFalse()
    {
        $response = $this->checkindex(array("is_favorite" => true), 9);

        $response = $this->checkPagination(1, array("is_favorite" => true), 5, 9);
    }

    public function checkIsFavoriteByUserId()
    {
        $response = $this->checkindex(array("is_favorite" => '#2'), 2);

        $response = $this->checkPagination(1, array("is_favorite" => 2), 2, 2);
    }
}
