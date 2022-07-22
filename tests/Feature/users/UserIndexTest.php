<?php

namespace Tests\Feature\users;

use App\Models\User;
use Database\Seeders\MasterUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserIndexTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {   $this->seed(MasterUserSeeder::class);
        $authModel = User::factory()->create();
        Sanctum::actingAs(
            $authModel,
            array('*')
        );
        $dataheaders['Authorization'] = 'Bearer ' . $authModel->createToken($authModel->id . $authModel->name . uniqid())->plainTextToken;

        // $createdModels = User::factory()->count(10)->create();
        // $this->assertDatabaseCount('users', 11);
        $response = $this->getJson(route('api.mobile.users.index'), $dataheaders);
        $response->dump();
        $response->assertStatus(200);

        $firstModel = $createdModels->first()->toArray();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('total')
                            ->has('per_page')
                            ->has('current_page')
                            ->has('last_page')
                            ->has('next_page_url')
                            ->has('prev_page_url')
                            ->has(
                                'users',
                                5,
                                fn ($json) =>
                                $json
                                    ->where('first_name', $authModel["first_name"])
                                    ->where('last_name', $authModel["last_name"])
                                    ->where('email', $authModel["email"])
                                    ->where('phone', (string) $authModel["phone"])
                                    ->where('birth_date', $authModel["birth_date"])
                                    ->where('id',  $authModel["id"])
                                    ->has('avatar')
                                    ->has('address')
                                    ->has('tickets')
                                //->has('google_token')
                            )
                    )
            );



        $response = $this->getJson(route('api.mobile.users.index', array("page" => "2")), $dataheaders);
        $response->assertStatus(200);

        $sixthtModel = $createdModels[4]->toArray();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('total')
                            ->has('per_page')
                            ->has('current_page')
                            ->has('last_page')
                            ->has('next_page_url')
                            ->has('prev_page_url')
                            ->has('users', 5, fn ($json) =>
                            $json
                                ->where('first_name', $sixthtModel["first_name"])
                                ->where('last_name', $sixthtModel["last_name"])
                                ->where('email', $sixthtModel["email"])
                                ->has('address')
                                ->has('tickets')
                                ->where('phone', (string) $sixthtModel["phone"])
                                ->where('birth_date', $sixthtModel["birth_date"])
                                ->where('id',  $sixthtModel["id"])
                                ->has('avatar'))

                    )
            );
    }
}
