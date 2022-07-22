<?php

namespace Tests\Feature\image;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ImageUpdateTest extends TestCase
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
        Storage::fake('s3');

        $file = UploadedFile::fake()->image('avatar.jpg');
        $data = [
            'image' => $file,
        ];
        $dataheaders['Authorization'] = 'Bearer ' . $createdModel->createToken($createdModel->id . $createdModel->name . uniqid())->plainTextToken;

        $response = $this->postJson('/api/mobile/images', $data, $dataheaders);
        $response->assertStatus(200);
        $this->assertDatabaseCount('images', 1);

        $id = $response->original["data"]["id"];
        $response = $this->deleteJson('/api/mobile/images', ['id' => $id, 'image' => $file,], $dataheaders);
        $response->assertStatus(200);
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
        $this->assertTrue(true);
    }
}
