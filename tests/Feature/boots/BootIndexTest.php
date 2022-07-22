<?php

namespace Tests\Feature\boots;

use App\Models\Ad;
use App\Models\Background;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Color;
use App\Models\Icon;
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
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BootIndexTest extends TestCase
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
        $product_total = 10;
        Stock::factory()
            ->has(Image::factory()->count(3), 'images')
            ->has(Video::factory()->count(3), 'videos')
            ->has(Category::factory()->count(3)->state(new Sequence(
                ['is_main' => true],
                ['is_main' => false],
            )), 'categories')
            ->for(Product::factory()
                ->has(Color::factory()->count(3), 'colors')
                ->has(Size::factory()->count(3), 'Sizes'))
            ->count($product_total)
            ->create();

        $this->assertDatabaseCount('stocks',  $product_total);
        $this->assertDatabaseCount('images',  $product_total * 8);
        $this->assertDatabaseCount('videos',  $product_total * 3);
        $this->assertDatabaseCount('categories',  $product_total * 3);
        $this->assertDatabaseCount('colors',  $product_total + 3);
        $this->assertDatabaseCount('sizes',  $product_total + 3);
        $this->assertDatabaseCount('products',  1);
        Banner::factory()->count(10)->create();
        $this->assertDatabaseCount('banners', 10);
        video::factory()->create([
            "url" => 'https://youtu.be/TTM6diVUqxw',
            "is_information" => true
        ]);
        Icon::factory()->create([
            "name" => $this->faker->word(),
            "is_favorite" => true,
            "color" => $this->faker->hexColor(),
            "image_id" => Image::factory()->create([
                'url' => 'https://www.vhv.rs/dpng/d/590-5909968_halloween-icon-png-free-png-download-transparent-halloween.png',
                'name' => $this->faker->word(),
            ]),
        ]);

        Ad::factory()->create([
            "name" => $this->faker->word(),
            "is_favorite" => true,
            "color" => $this->faker->hexColor(),
            "image_id" => Image::factory()->create([
                'url' => 'https://searchengineland.com/figz/wp-content/seloads/2017/02/google-adwords-green-outline-ad2-2017-1920-800x450.jpg',
                'name' => $this->faker->word(),
            ]),
        ]);

        Background::factory()->create([
            "is_favorite" => true,
            "color" => $this->faker->hexColor(),
            "image_id" => Image::factory()->create([
                'url' => 'https://searchengineland.com/figz/wp-content/seloads/2017/02/google-adwords-green-outline-ad2-2017-1920-800x450.jpg',
                'name' => $this->faker->word(),
            ]),
        ]);
    }

    public function checkindex(): void
    {
        $response = $this->getJson(route('api.mobile.boots.index'));
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has(
                            'latest_stocks',
                            fn ($json) =>
                            $json->has('total')
                                ->where('total', 10)
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('stocks', 5, fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('price')
                                    ->has('mock_price')
                                    ->has('credits')
                                    ->has('ribbon')
                                    ->has('is_available')
                                    ->has('is_favorite')
                                    ->has('categories')
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
                                    ->has('color')
                                    ->has('colors')
                                    //->has('size')
                                    ->has('sizes'))
                        )->has(
                            'stocks',
                            fn ($json) =>
                            $json->has('total')
                                ->where('total', 10)
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('stocks', 5, fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('price')
                                    ->has('ribbon')
                                    ->has('is_available')
                                    ->has('is_favorite')
                                    ->has('mock_price')
                                    ->has('credits')
                                    ->has('categories')
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
                                    ->has('color')
                                    ->has('colors')
                                    //   ->has('size')
                                    ->has('sizes'))
                        )->has(
                            "banners"

                        )->has(
                            "icons",
                            fn ($json) =>
                            $json->has('total')
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has(
                                    'icons',
                                    1,
                                    fn ($json) =>
                                    $json
                                        ->has('id')
                                        ->has('name')
                                        ->has('is_favorite')
                                        ->has('color')
                                        ->has('image', fn ($json) =>
                                        $json
                                            ->has('id')
                                            ->has('name')
                                            ->has('url'))
                                )
                        )->has(
                            "ads",
                            fn ($json) =>
                            $json->has('total')
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has(
                                    'ads',
                                    1,
                                    fn ($json) =>
                                    $json
                                        ->has('id')
                                        ->has('name')
                                        ->has('is_favorite')
                                        ->has('color')
                                        ->has('image', fn ($json) =>
                                        $json
                                            ->has('id')
                                            ->has('name')
                                            ->has('url'))
                                )
                        )
                            ->has(
                                "backgrounds",
                                fn ($json) =>
                                $json->has('total')
                                    ->has('per_page')
                                    ->has('current_page')
                                    ->has('last_page')
                                    ->has('next_page_url')
                                    ->has('prev_page_url')
                                    ->has(
                                        'backgrounds',
                                        1,
                                        fn ($json) =>
                                        $json
                                            ->has('id')

                                            ->has('is_favorite')
                                            ->has('color')
                                            ->has('image', fn ($json) =>
                                            $json
                                                ->has('id')
                                                ->has('name')
                                                ->has('url'))
                                    )
                            )
                            ->has(
                                'videos',
                                fn ($json) =>
                                $json->has('total')
                                    ->has('per_page')
                                    ->has('current_page')
                                    ->has('last_page')
                                    ->has('next_page_url')
                                    ->has('prev_page_url')
                                    ->has('videos', 1, fn ($json) =>
                                    $json->has('id')
                                        ->has('name')
                                        ->has('url')
                                        ->has('is_information'))
                            )
                            ->has(
                                "categories",
                                30,
                                fn ($json) =>
                                $json
                                    ->has('id')
                                    ->has('name')
                                    ->has('is_main')
                                    ->has('color')
                                    ->has('image', fn ($json) =>
                                    $json->has('id')
                                        ->has('url')
                                        ->has('name'))
                            )->has("auth", fn ($json) =>
                            $json
                                ->has('first_name')
                                ->has('last_name')
                                ->has('email')
                                ->has('avatar')
                                ->has('address')
                                ->has('tickets')
                                ->has('phone')
                                ->has('birth_date')
                                ->has('id'))
                    )
            );
    }





    public function checkLatestLimitIndex(): void
    {
        $params = array("stock_limit" => 2);
        $response = $this->getJson(route('api.mobile.boots.index', $params));
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has(
                            'latest_stocks',
                            fn ($json) =>
                            $json->has('total')
                                ->where('total', 10)
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('stocks', 2, fn ($json) =>
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
                                    ->has('videos', 3, fn ($json) =>
                                    $json->has('id')
                                        ->has('url')
                                        ->has('name'))
                                    ->has('description')
                                    ->has('name')
                                    ->has('color')
                                    ->has('colors')
                                    ->has('size')
                                    ->has('sizes'))
                        )->has(
                            'stocks',
                            fn ($json) =>
                            $json->has('total')
                                ->where('total', 10)
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('stocks', 5, fn ($json) =>
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
                                    ->has('videos', 3, fn ($json) =>
                                    $json->has('id')
                                        ->has('url')
                                        ->has('name'))
                                    ->has('description')
                                    ->has('name')
                                    ->has('color')
                                    ->has('colors')
                                    ->has('size')
                                    ->has('sizes'))
                        )->has(
                            "banners",
                            fn ($json) =>
                            $json->has('total')
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has(
                                    'banners',
                                    5,
                                    fn ($json) =>
                                    $json
                                        ->has('id')
                                        ->has('name')
                                        ->has('is_favorite')
                                        ->has('image')
                                )
                        )->has(
                            "categories",
                            30,
                            fn ($json) =>
                            $json
                                ->has('id')
                                ->has('name')
                                ->has('is_main')
                                ->has('color')
                                ->has('image', fn ($json) =>
                                $json->has('id')
                                    ->has('url')
                                    ->has('name'))
                        )->has("auth", fn ($json) =>
                        $json
                            ->has('first_name')
                            ->has('last_name')
                            ->has('email')

                            ->has('phone')
                            ->has('birth_date')
                            ->has('id'))
                    )
            );
    }
    public function checkStockLimitIndex(): void
    {
        $params = array("stock_limit" => 2);
        $response = $this->getJson(route('api.mobile.boots.index', $params));
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has(
                            'latest_stocks',
                            fn ($json) =>
                            $json->has('total')
                                ->where('total', 10)
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('stocks', 5, fn ($json) =>
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
                                    ->has('videos', 3, fn ($json) =>
                                    $json->has('id')
                                        ->has('url')
                                        ->has('name'))
                                    ->has('description')
                                    ->has('name')
                                    ->has('color')
                                    ->has('colors')
                                    ->has('size')
                                    ->has('sizes'))
                        )->has(
                            'stocks',
                            fn ($json) =>
                            $json->has('total')
                                ->where('total', 10)
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has('stocks', 2, fn ($json) =>
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
                                    ->has('videos', 3, fn ($json) =>
                                    $json->has('id')
                                        ->has('url')
                                        ->has('name'))
                                    ->has('description')
                                    ->has('name')
                                    ->has('color')
                                    ->has('colors')
                                    ->has('size')
                                    ->has('sizes'))
                        )->has(
                            "banners",
                            fn ($json) =>
                            $json->has('total')
                                ->has('per_page')
                                ->has('current_page')
                                ->has('last_page')
                                ->has('next_page_url')
                                ->has('prev_page_url')
                                ->has(
                                    'banners',
                                    5,
                                    fn ($json) =>
                                    $json
                                        ->has('id')
                                        ->has('name')
                                        ->has('is_favorite')
                                        ->has('image')
                                )
                        )->has(
                            "categories",
                            30,
                            fn ($json) =>
                            $json
                                ->has('id')
                                ->has('name')
                                ->has('is_main')
                                ->has('color')
                                ->has('image', fn ($json) =>
                                $json->has('id')
                                    ->has('url')
                                    ->has('name'))
                        )->has("auth", fn ($json) =>
                        $json
                            ->has('first_name')
                            ->has('last_name')
                            ->has('email')

                            ->has('phone')
                            ->has('birth_date')
                            ->has('id'))
                    )
            );
    }
}
