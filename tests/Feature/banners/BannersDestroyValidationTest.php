<?php

namespace Tests\Feature\banners;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BannersDestroyValidationTest extends TestCase
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
        $this->assertDatabaseCount('users', 1);
        Sanctum::actingAs(
            $AuthdUser,
            array('*')
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $AuthdUser->createToken($AuthdUser->id . $AuthdUser->name . uniqid())->plainTextToken;

        $createdModel = Banner::factory()->create();
        $response = $this->deleteJson(route('api.mobile.banners.destroy'), array('id' => 44), $this->dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('banners', 1);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );


        $invalidBody = array();
        $response = $this->deleteJson(route('api.mobile.banners.destroy'), $invalidBody, $this->dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('banners', 1);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );




        $invalidBody = array(
            'id' => "wssss1",
        );;
        $response = $this->deleteJson(route('api.mobile.banners.destroy'), $invalidBody, $this->dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('banners', 1);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );
    }
}
