<?php

namespace Tests\Feature\cateories;

use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryIndexTest extends TestCase
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
        $authdUser = User::factory()->create();
        Sanctum::actingAs(
            $authdUser,
            ['*']
        );
        $this->createdModel = Category::Factory()->has(Image::factory(), 'image')->create([
            'name' => 'aaaaaaaaaaaaaaaaaa',
        ]);
        $this->createdModels = Category::Factory()->has(Image::factory(), 'image')->count(9)->create();
        $this->assertDatabaseCount('categories', 10);
        $this->assertDatabaseCount('images', 10);
    }

    public function checkindex(): void
    {
        $response = $this->getJson(route('api.mobile.categories.index'));
        $response->assertStatus(200);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        10,
                        fn ($json) =>
                        $json
                            ->where('id',   $this->createdModel["id"])
                            ->where('name',  $this->createdModel["name"])
                            ->has('is_main')
                            ->has('color')
                            ->has('image')->etc()
                    )
            );
    }
}
