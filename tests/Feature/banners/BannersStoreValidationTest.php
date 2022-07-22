<?php

namespace Tests\Feature\banners;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BannersStoreValidationTest extends TestCase
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
        Storage::fake('s3');
        $AuthdUser = User::factory()->create();
        Sanctum::actingAs(
            $AuthdUser,
            array('*')
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $AuthdUser->createToken($AuthdUser->id . $AuthdUser->name . uniqid())->plainTextToken;


        $file =        UploadedFile::fake()->image('avatar.jpg');
        $body = Banner::factory()->make(array("is_favorite" => "sss", "image" => $file))->toArray();
        $response = $this->postJson(route('api.mobile.banners.store'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('banners', 0);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1

                            ->has('is_favorite')

                    )
            );

        $body = array();
        $response = $this->postJson(route('api.mobile.banners.store'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('banners', 0);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('image')
                            ->has('is_favorite')
                            ->has('name')
                            ->has('group_number')

                    )
            );
    }
}
