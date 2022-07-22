<?php

namespace Tests\Feature\users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserUpdateValidationTest extends TestCase
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

        $password = $this->faker->password();
        $createdModel = User::factory()->create(array("password" => $password));
        $this->assertModelExists($createdModel);
        $this->assertDatabaseCount('users', 2);
        $this->assertDatabaseHas('users', $createdModel->getOriginal());
        $createdModel->password =  "sss";
        $createdModel->email = "ssss";
        $createdModel->phone = "ssss";
        $createdModel->birth_date = "ssss";
        $createdModel->id =  44;
        $body = $createdModel->toArray();
        $body["password"] = "sss";
        $response = $this->putJson(route('api.mobile.users.update'), $body, $dataheaders);
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
                            ->has('email')
                            ->has('phone')
                          
                            ->has('id')
                    )
            );

        $body = array();
        $response = $this->putJson(route('api.mobile.users.update'), $body, $dataheaders);
        $response->assertUnprocessable();
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



        $user = User::factory()->create();

        $body = User::factory()->create(array(
            "birth_date" => "2021/08/14"
        ))->toArray();
        $body["email"] = $user->email;
        $response = $this->putJson(route('api.mobile.users.update'), $body, $dataheaders);
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



        // $invalidBody = array(
        //     'id' => "wssss1",
        // );;
        // $response = $this->putJson(route('api.mobile.users.update'), $invalidBody);
        // $response->assertUnprocessable();
        // $response
        //     ->assertJson(
        //         fn (AssertableJson $json) =>
        //         $json->has('message')
        //             ->has(
        //                 'data',
        //                 fn ($json1) =>
        //                 $json1
        //                     ->has('first_name')
        //                     ->has('last_name')
        //                     ->has('password')
        //                     ->has('email')
        //                     ->has('phone')
        //                     ->has('birth_date')
        //                     ->has('id')
        //             )
        //     );
    }
}
