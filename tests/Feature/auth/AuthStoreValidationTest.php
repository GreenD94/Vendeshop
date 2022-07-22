<?php

namespace Tests\Feature\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class AuthStoreValidationTest extends TestCase
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
        $this->checkEmailIsEmail();
        $this->checkEmailIsExists();
        $this->checkEmailIsRequiredIfDriverIsNull();
        $this->checkPasswordMin6();
        $this->checkPasswordRequiredIfDriverIsNull();
        $this->checkTokenRequiredIfDriverIsGoogle();
        $this->checkDriverIsValid();
        $this->checkDeviceKeyRequired();
    }
    public function checkData()
    {
        $this->model = User::factory()->create(["email" => "email@email.com"]);
        $response = $this->getJson(route('api.mobile.stocks.index'));
        // $response->assertStatus(401);
    }
    public function checkEmailIsEmail()
    {
        $params = array("email" => "sssssss", "password" => "sssssssssssss", "device_key" => "sss");
        $response = $this->post(route("api.mobile.auth.store"), $params);
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
    public function checkPasswordMin6()
    {
        $params = array("email" => "email@email.com", "password" => "2", "device_key" => "sss");
        $response = $this->post(route("api.mobile.auth.store"), $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('password')
                    )
            );
    }
    public function checkPasswordRequiredIfDriverIsNull()
    {
        $params = array("email" => "email@email.com", "device_key" => "sss");
        $response = $this->post(route("api.mobile.auth.store"), $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('password')
                    )
            );
    }
    public function checkEmailIsRequiredIfDriverIsNull()
    {
        $params = array("password" => "2wwwwwwwwwwwwwwww", "device_key" => "sss");
        $response = $this->post(route("api.mobile.auth.store"), $params);
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
    public function checkTokenRequiredIfDriverIsGoogle()
    {
        $params = array("driver" => "google", "device_key" => "sss");
        $response = $this->post(route("api.mobile.auth.store"), $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('token')
                    )
            );
    }
    public function checkDriverIsValid()
    {
        $params = array("token" => "ssssssssssss", "driver" => "wwwwww", "device_key" => "sss");
        $response = $this->post(route("api.mobile.auth.store"), $params);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('driver')
                            ->has('email')
                            ->has('password')
                    )
            );
    }
    public function checkEmailIsExists()
    {
        $params = array("email" => "email@email2.com", "password" => "2wwwwwwwwwwwwwwww", "device_key" => "sss");
        $response = $this->post(route("api.mobile.auth.store"), $params);
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

    public function checkDeviceKeyRequired()
    {
        $params = array("email" => "email@email.com", "password" => "2wwwwwwwwwwwwwwww",);
        $response = $this->post(route("api.mobile.auth.store"), $params);

        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('device_key')
                    )
            );
    }
}
