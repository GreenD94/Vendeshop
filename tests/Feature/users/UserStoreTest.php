<?php

namespace Tests\Feature\users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserStoreTest extends TestCase
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

        $password = "sssssssss";
        $body = User::factory()->make()->toArray();
        $body["password"] = $password;
        $response = $this->postJson(route('api.mobile.users.store'), $body);
        $response->assertStatus(200);
        $this->assertDatabaseCount('users', 1);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->where('first_name', $body["first_name"])
                            ->where('last_name', $body["last_name"])
                            ->where('email', $body["email"])
                            ->where('phone', $body["phone"])
                            ->where('birth_date', $body["birth_date"])
                            ->has('id')
                            ->has('avatar')
                            ->has('address')
                            ->has('tickets')

                    )
            );
        $this->assertTrue(Hash::check($body["password"], $response->original["data"]["password"]));
    }
}
