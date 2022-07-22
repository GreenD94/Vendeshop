<?php

namespace Tests\Feature\auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthIndexTest extends TestCase
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
        $this->checkIndex();
    }

    public function checkData()
    {
        $authdUser = User::factory()->create();
        Sanctum::actingAs(
            $authdUser,
            ['*']
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $authdUser->createToken($authdUser->id . $authdUser->name . uniqid())->plainTextToken;
    }

    public function checkIndex()
    {

        $response = $this->getJson(route("api.mobile.auth.index"), $this->dataheaders);
        $response->dump();
        $response->assertStatus(200);
    }
}
