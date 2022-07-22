<?php

namespace Tests\Feature\banners;

use App\Models\Banner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class BannersIndexTest extends TestCase
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
        $createdModels = Banner::factory()->count(10)->create();
        $this->assertDatabaseCount('banners', 10);
        $response = $this->getJson(route('api.mobile.banners.index', ['page' => 1]), $this->dataheaders);
        $response->assertStatus(200);

        $firstModel = $createdModels[9]->toArray();

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data'
                    )
            );

        $response = $this->getJson(route('api.mobile.banners.index', array("page" => "2")), $this->dataheaders);
        $response->assertStatus(200);

        $sixthtModel = $createdModels[4]->toArray();

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data'

                    )
            );
    }
}
