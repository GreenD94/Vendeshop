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

class CategoryStoreValidationTest extends TestCase
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
        $this->checkNameIsRequired();
        $this->checkNameIsUnique();
        $this->checkIsMainIsRequired();
        $this->checkIsMainIsBool();
        $this->checkColorRequired();
        $this->checkImageIdRequired();
        $this->checkImageIdExists();
        $this->checkImageIdIsIntenger();
        $this->checkImageIdIsNumeric();
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
    public function checkNameIsRequired()
    {
        $params = array();
        $response = $this->postJson(route('api.mobile.categories.store'),  $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('name')->etc()
                    )
            );
    }
    public function checkNameIsUnique()
    {
        $params = array("name" => $this->createdModel->name);
        $response = $this->postJson(route('api.mobile.categories.store'),  $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('name')->etc()
                    )
            );
    }
    public function checkIsMainIsRequired()
    {
        $params = array();
        $response = $this->postJson(route('api.mobile.categories.store'),  $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('is_main')->etc()
                    )
            );
    }
    public function checkIsMainIsBool()
    {
        $params = array("is_main" => "ss");
        $response = $this->postJson(route('api.mobile.categories.store'),  $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('is_main')->etc()
                    )
            );
    }
    public function checkColorRequired()
    {
        $params = array();
        $response = $this->postJson(route('api.mobile.categories.store'),  $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('color')->etc()
                    )
            );
    }
    public function checkImageIdRequired()
    {
        $params = array();
        $response = $this->postJson(route('api.mobile.categories.store'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('image')->etc()
                    )
            );
    }
    public function checkImageIdExists()
    {
        $params = array("image_id" => 22);
        $response = $this->postJson(route('api.mobile.categories.store'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('image')->etc()
                    )
            );
    }
    public function checkImageIdIsIntenger()
    {
        $params = array("image_id" => 1.1);
        $response = $this->postJson(route('api.mobile.categories.store'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('image')->etc()
                    )
            );
    }
    public function checkImageIdIsNumeric()
    {
        $params = array("image_id" => "aaa");
        $response = $this->postJson(route('api.mobile.categories.store'), $params, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('image')->etc()
                    )
            );
    }
}
