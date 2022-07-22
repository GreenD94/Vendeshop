<?php

namespace Tests\Feature\stock;

use App\Models\Color;
use App\Models\Image;
use App\Models\Ribbon;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class StockStoreTest extends TestCase
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
        $createdModel = User::factory()->create();
        Sanctum::actingAs(
            $createdModel,
            ['*']
        );
        $dataheaders['Authorization'] = 'Bearer ' . $createdModel->createToken($createdModel->id . $createdModel->name . uniqid())->plainTextToken;
        Storage::fake('s3');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $mock_price=$this->faker->numberBetween(1, 100);
        $price= $this->faker->numberBetween(1, $mock_price);
        $body = [
            'price' =>$price,
            'mock_price' => $mock_price,
            'credits'  => $this->faker->randomNumber(),
            'discount'   => $this->faker->randomNumber(),
            'description' => $this->faker->sentence(),
            'name' => $this->faker->name(),
            'cover_image' => $file,
            'images' => [$file],
            'color_id' => Color::factory()->create()->id,
            'size_id' => Size::factory()->create()->id,
            'ribbon_id' => Ribbon::factory()->create()->id
        ];
 
        $response = $this->postJson('/api/mobile/stocks', $body, $dataheaders);

        $response->assertStatus(200);


        $this->assertDatabaseCount('stocks', 1);
        $this->assertDatabaseCount('colors', 1);
        $this->assertDatabaseCount('images', 3);
        $this->assertDatabaseCount('sizes', 1);
        $this->assertDatabaseCount('ribbons', 1);




        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1->has('price')
                            ->has('mock_price')
                            ->has('credits')
                            ->has('discount')
                            ->has('name')
                            ->has('description')
                            ->has('id')
                            ->has('color')

                            //->has('size')

                            ->has('cover_image')
                            ->has('ribbon')
                            ->where('price', $body["price"])
                            ->where('mock_price', $body["mock_price"])
                            ->where('credits', $body["credits"])
                     
                            ->where('name', $body["name"])
                            ->where('description', $body["description"])
                            ->etc()
                    )
            );
    }
}
