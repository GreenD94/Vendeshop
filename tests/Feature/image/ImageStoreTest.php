<?php

namespace Tests\Feature\image;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;

class ImageStoreTest extends TestCase
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
        $data = [
            'image' => $file,
        ];
        $response = $this->postJson('/api/mobile/images', $data, $dataheaders);
        $response->assertStatus(200);

        $this->assertDatabaseCount('images', 1);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json
                            ->has('id')
                            ->has('url')
                            ->has('name')
                    )
            );
        $this->assertNotNull($response->original["data"]["url"]);
        $this->assertTrue(true);
    }
}
