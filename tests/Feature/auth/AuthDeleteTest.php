<?php

namespace Tests\Feature\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthDeleteTest extends TestCase
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
        $password = $this->faker->password();
        $body = [
            'email' => $this->faker->email(),
            'password' => Hash::make($password),
            "device_key" => "ssss"
        ];
        $model = User::factory()->create($body);
        $this->assertDatabaseCount('users', 1);
        $body["password"] = $password;

        $response = $this->deleteJson('/api/mobile/auth');
        $response->assertStatus(401);

        $response = $this->post(route("api.mobile.auth.store"), $body);
        $response->assertStatus(200);
        $rawToken = $response->original["data"]["token"];

        $response = $this->deleteJson('/api/mobile/auth', [], ['Authorization' => 'Bearer ' . $rawToken]);
        $response->assertStatus(200);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has('data')
            );
        $this->app->get('auth')->forgetGuards();

        $response = $this->deleteJson('/api/mobile/auth', [], ['Authorization' => 'Bearer ' . $rawToken . '2']);
        $response->assertStatus(401);
    }
}
