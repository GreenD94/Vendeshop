<?php

namespace Tests\Feature\ribbon;

use App\Models\Ribbon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RibbonIndexTest extends TestCase
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
        Storage::fake('s3');
        Sanctum::actingAs(
            $createdModel,
            ['*']
        );


        $dataheaders['Authorization'] = 'Bearer ' . $createdModel->createToken($createdModel->id . $createdModel->name . uniqid())->plainTextToken;

        $model = Ribbon::factory()->create();

        Ribbon::factory()->count(9)->create();

        $this->assertDatabaseCount('ribbons', 10);

        $response = $this->getJson(route('api.mobile.ribbons.index', ["page" => 1]), $dataheaders);


        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->where('total', 10)
                            ->has('total')
                            ->has('per_page')
                            ->has('current_page')
                            ->has('last_page')
                            ->has('next_page_url')
                            ->has('prev_page_url')
                            ->has(
                                'ribbons',
                                5,
                                fn ($json) =>  $json
                                    ->has('url')

                                    ->has('id')
                            )
                    )
            );
    }
}
