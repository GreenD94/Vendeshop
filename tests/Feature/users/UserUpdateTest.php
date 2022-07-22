<?php

namespace Tests\Feature\users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserUpdateTest extends TestCase
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
        Sanctum::actingAs(
            $createdModel,
            array('*')
        );
        $dataheaders['Authorization'] = 'Bearer ' . $createdModel->createToken($createdModel->id . $createdModel->name . uniqid())->plainTextToken;

        $password = $this->faker->password(7);
        $createdModel = User::factory()->create(array("password" => $password));
        $this->assertModelExists($createdModel);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', $createdModel->getOriginal());
        $createdModel->first_name = "ssss";
        $body = $createdModel->toArray();
        $body["password"] = $password;
        $response = $this->putJson(route('api.mobile.users.update'), $body, $dataheaders);
        $response->assertStatus(200);
        $createdModel = $createdModel->toArray();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->where('first_name', $createdModel["first_name"])
                            ->where('last_name', $createdModel["last_name"])
                            ->where('email', $createdModel["email"])

                            ->where('phone', (string) $createdModel["phone"])
                            ->where('birth_date', $createdModel["birth_date"])
                            ->where('id',  $createdModel["id"])
                            ->has('address')
                            ->has('tickets')
                            ->has('avatar')
                    )
            );
        $this->assertTrue(Hash::check($password, $response->original["data"]["password"]));
    }
}
