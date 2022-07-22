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

class CategoryUpdateTest extends TestCase
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
        $this->checkUpdate();
    }

    public function checkData()
    {
        $authdUser = User::factory()->create();
        Sanctum::actingAs(
            $authdUser,
            ['*']
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $authdUser->createToken($authdUser->id . $authdUser->name . uniqid())->plainTextToken;

        $this->createdModel = Category::Factory()->has(Image::factory(), 'image')->create();
        $this->assertModelExists($this->createdModel);
        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseCount('images', 1);
    }

    public function checkUpdate()
    {
        $this->createdModel->name = "ssss";
        $response = $this->putJson(route('api.mobile.categories.update'), $this->createdModel->toArray(), $this->dataheaders);

        $response->assertStatus(200);

        $createdModel = $this->createdModel->toArray();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->where('id', $createdModel["id"])
                            ->where('name', $createdModel["name"])
                            ->has('is_main')
                            ->has('color')
                            ->has('image', fn ($json) =>
                            $json->has('id')
                                ->has('url')
                                ->has('name'))
                    )
            );
    }
}
