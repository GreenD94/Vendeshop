<?php

namespace Tests\Feature\users;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserdestroyTest extends TestCase
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
            ['*']
        );
        $dataheaders['Authorization'] = 'Bearer ' . $createdModel->createToken($createdModel->id . $createdModel->name . uniqid())->plainTextToken;

        $this->assertModelExists($createdModel);
        $this->assertDatabaseCount('users', 1);

        $response = $this->deleteJson(route('api.mobile.users.destroy'), $createdModel->toArray(), $dataheaders);
        $response->assertStatus(200);
        $this->assertModelMissing($createdModel);
        $this->assertDeleted($createdModel);

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

                            ->has('avatar')
                            ->has('tickets')
                    )
            );
    }
}
