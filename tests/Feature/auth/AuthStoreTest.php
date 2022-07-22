<?php

namespace Tests\Feature\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthStoreTest extends TestCase
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

        $this->checkData();
        $this->checkStore();
        //$this->checkGoogleStore();
    }

    public function checkData()
    {
        $password = $this->faker->password();
        $this->googleBody = ["token" => uniqid(), "driver" => "google"];
        $this->body = [
            'email' => $this->faker->email(),
            'password' => Hash::make($password),

            "device_key" => "aaaaaaaaaaaaa"
        ];
        $this->model = User::factory()->create($this->body);
        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseHas('users',   $this->body);
        $this->body["password"] = $password;


        $response = $this->getJson(route('api.mobile.stocks.index'));
        // $response->assertStatus(401);
    }
    public function checkStore()
    {

        $response = $this->post(route("api.mobile.auth.store"),  $this->body);
        $response->assertStatus(200);
        $rawToken = $response->original["data"]["token"];
        $this->model->refresh();
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $query = DB::table('personal_access_tokens')->where('id',  $this->model->tokens[0]->id);
        $personal_access_token =  $query->first();
        $this->assertDatabaseHas('personal_access_tokens', (array) $personal_access_token);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('token')
                    )
            );

        $response = $this->getJson(route('api.mobile.stocks.index'), ['Authorization' => 'Bearer ' . $rawToken]);
        $response->assertStatus(200);
    }

    public function checkGoogleStore()
    {

        $response = $this->post(route("api.mobile.auth.store"), $this->googleBody);

        $response->assertStatus(200);
        $rawToken = $response->original["data"]["token"];
        $this->model->refresh();
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $query = DB::table('personal_access_tokens')->where('id',  $this->model->tokens[0]->id);
        $personal_access_token =  $query->first();
        $this->assertDatabaseHas('personal_access_tokens', (array) $personal_access_token);
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json) =>
                        $json->has('token')
                    )
            );

        $response = $this->getJson(route('api.mobile.stocks.index'), ['Authorization' => 'Bearer ' . $rawToken]);
        $response->assertStatus(200);
    }
}
