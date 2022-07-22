<?php

namespace Tests\Feature\ribbon;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RibbonStoreTest extends TestCase
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
        $response = $this->postJson(route('api.mobile.ribbons.store'), $data, $dataheaders);
        $response->assertStatus(200);

        $this->assertDatabaseCount('images', 1);
        $this->assertDatabaseCount('ribbons', 1);

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

                    )
            );

        // $this->assertNotNull($response->original["data"]["url"]);
    }
}
