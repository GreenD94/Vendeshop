<?php

namespace Tests\Feature\stock;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class StockDeleteTest extends TestCase
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
        $this->assertModelExists($createdModel);
        $this->assertDatabaseCount('stocks', 1);
        $this->assertDatabaseCount('colors', 1);
        $this->assertDatabaseCount('images', 2);
        $this->assertDatabaseCount('sizes', 1);

        $response = $this->deleteJson('/api/mobile/stocks', $createdModel->toArray(), $dataheaders);

        $response->assertStatus(200);
        $this->assertModelMissing($createdModel);
        $this->assertDeleted($createdModel);



        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1->has('price')
                            ->has('cover_image')
                            ->has('ribbon')
                            ->has('images')
                            ->has('videos')
                            ->has('color')
                            //->has('size')
                            ->has('colors')
                            ->has('sizes')
                            ->has('mock_price')
                            ->has('credits')
                            ->has('discount')
                            ->has('is_available')
                            ->has('is_favorite')
                            ->has('categories')
                            ->has('name')
                            ->has('description')
                            ->has('id')
                            ->where('price',  (string) $createdModel->price)
                            ->where('mock_price',  $createdModel->mock_price)
                            ->where('credits', $createdModel->credits)
                         
                            ->where('name',  $createdModel->name)
                            ->where('description',  $createdModel->description)
                            ->where('id',  $createdModel->id)
                    )
            );
    }
}
