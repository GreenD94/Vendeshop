<?php

namespace Tests\Feature\seeders;

use App\Models\User;
use Database\Seeders\MasterUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class MasterUserMigrateTest extends TestCase
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



        $password = "master@master.com";
        $body = [
            'email' => "master@master.com",
            'password' => Hash::make($password),
            'device_key' => 'sss'
        ];
        $this->seed(MasterUserSeeder::class);
        $this->assertDatabaseCount('users', 1);
        $model = User::where("email", "master@master.com")->first();
        $body["password"] = $password;

        $response = $this->post(route("api.mobile.auth.store"), $body);

        $response->assertStatus(200);
        $rawToken = $response->original["data"]["token"];
        $model->refresh();
        $this->assertDatabaseCount('personal_access_tokens', 1);
        $query = DB::table('personal_access_tokens')->where('id', $model->tokens[0]->id);
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

        $response = $this->getJson('/api/mobile/stocks', ['Authorization' => 'Bearer ' . $rawToken]);
        $response->assertStatus(200);
    }
}
