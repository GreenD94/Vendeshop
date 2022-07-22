<?php

namespace Tests\Feature\banners;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BannersUpdateTest extends TestCase
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
        $AuthdUser = User::factory()->create();
        Sanctum::actingAs(
            $AuthdUser,
            array('*')
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $AuthdUser->createToken($AuthdUser->id . $AuthdUser->name . uniqid())->plainTextToken;

        $createdModel = Banner::factory()->create();
        $this->assertModelExists($createdModel);
        $this->assertDatabaseCount('banners', 1);
        $this->assertDatabaseHas('banners', $createdModel->getOriginal());
        $createdModel->name = "ssss";
        $response = $this->putJson(route('api.mobile.banners.update'), $createdModel->toArray(), $this->dataheaders);

        $response->assertStatus(200);

        $createdModel = $createdModel->toArray();
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
                            ->has('is_favorite')
                            ->has('image')
                    )
            );
    }
}
