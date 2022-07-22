<?php

namespace Tests\Feature\cateories;

use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryDestroyTest extends TestCase
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
        $this->checkDestroy();
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

        $this->assertDatabaseCount('categories', 1);
        $this->assertDatabaseCount('images', 1);
    }
    public function checkDestroy()
    {
        $response = $this->deleteJson(route('api.mobile.categories.destroy'), $this->createdModel->toArray(),  $this->dataheaders);
        $response->assertStatus(200);
        //$this->assertModelMissing($this->createdModel);
        // $this->assertDeleted($this->createdModel);
        $this->assertDatabaseCount('categories', 0);

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
