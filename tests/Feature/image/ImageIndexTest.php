<?php

namespace Tests\Feature\image;

use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ImageIndexTest extends TestCase
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
        Storage::fake('s3');
        Sanctum::actingAs(
            $createdModel,
            ['*']
        );

        $dataheaders['Authorization'] = 'Bearer ' . $createdModel->createToken($createdModel->id . $createdModel->name . uniqid())->plainTextToken;

        $modelData = [
            'url' => $this->faker->imageUrl(),
            'name' => $this->faker->userName()
        ];
        $model = Image::factory()->create($modelData);

        Image::factory()->count(9)->create();

        $this->assertDatabaseCount('images', 10);
        $this->assertDatabaseHas('images', $modelData);

        $response = $this->getJson('/api/mobile/images?page=1', $dataheaders);


        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->where('total', 10)
                            ->has('total')
                            ->has('per_page')
                            ->has('current_page')
                            ->has('last_page')
                            ->has('next_page_url')
                            ->has('prev_page_url')
                            ->has(
                                'images',
                                5,
                                fn ($json) =>  $json
                                    ->has('url')
                                    ->has('name')
                                    ->has('id')
                            )
                    )
            );
    }
}
