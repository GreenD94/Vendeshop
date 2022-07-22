<?php

namespace Tests\Feature\users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class UserStoreValidationTest extends TestCase
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

        $password = "ss";
        $body = User::factory()->make(array(
            "password" => $password,
            "email" => "sss",
            "phone" => "sss",
            "birth_date" => "ss"
        ))->toArray();
        $response = $this->postJson(route('api.mobile.users.store'), $body);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', 0);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('password')
                            ->has('email')
                            ->has('phone')
                            
                    )
            );

        $body = array();
        $response = $this->postJson(route('api.mobile.users.store'), $body);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', 0);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('first_name')
                            ->has('last_name')
                            ->has('password')
                            ->has('email')
                            ->has('phone')
                            ->has('birth_date')
                    )
            );

        $createdModel = User::factory()->create();
        $body = User::factory()->make(array(
            "birth_date" => "2021/08/14",
            "email" => $createdModel->email
        ))->toArray();
        $body["password"] = "sssssss";
        $response = $this->postJson(route('api.mobile.users.store'), $body);
        $response->assertUnprocessable();

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                         
                            ->has('email')
                    )
            );
    }
}
