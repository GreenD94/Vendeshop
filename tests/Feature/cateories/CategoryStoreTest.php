<?php

namespace Tests\Feature\cateories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryStoreTest extends TestCase
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
        $this->checkStore();
    }
    public function checkData(): void
    {
        $authdUser = User::factory()->create();
        Sanctum::actingAs(
            $authdUser,
            ['*']
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $authdUser->createToken($authdUser->id . $authdUser->name . uniqid())->plainTextToken;
        Storage::fake('s3');
        $this->httpbody = [
            "name" => "test",
            "color" => "ss",
            "is_main" => true,
            "image" => UploadedFile::fake()->image('avatar.jpg')
        ];
    }
    public function checkStore(): void
    {
        $response = $this->postJson(route('api.mobile.categories.store'),  $this->httpbody,  $this->dataheaders);

        $response->assertStatus(200);

        $this->assertDatabaseCount('categories', 1);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                            ->where('name', $this->httpbody["name"])
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
