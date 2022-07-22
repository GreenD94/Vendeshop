<?php

namespace Tests\Feature\users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserdestroyValidationTest extends TestCase
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

        $createdUser = User::factory()->create();
        $this->assertDatabaseCount('users', 1);
        Sanctum::actingAs(
            $createdUser,
            array('*')
        );
        $dataheaders['Authorization'] = 'Bearer ' . $createdUser->createToken($createdUser->id . $createdUser->name . uniqid())->plainTextToken;
        $createdModel = User::factory()->create();
        $invalidBody = array(
            'id' => 44,
        );
        $response = $this->deleteJson(route('api.mobile.users.destroy'), array('id' => $invalidBody), $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', 2);
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
        $response = $this->deleteJson(route('api.mobile.users.destroy'), $invalidBody, $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', 2);
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
        $response = $this->deleteJson(route('api.mobile.users.destroy'), $invalidBody, $dataheaders);
        $response->assertUnprocessable();
        $this->assertDatabaseCount('users', 2);
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
