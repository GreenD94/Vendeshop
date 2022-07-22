<?php

namespace Tests\Feature\cateories;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CategoryDestroyValidationTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    /**
     * A basic feature test example. php artisan make:test boots/BootIndexTest

     *
     * @return void
     */
    public function test_example()
    {
        $this->checkData();
        $this->checkIdRequired();
        $this->checkIdExists();
        $this->checkIdIsIntenger();
        $this->checkIdIsNumeric();
        $this->checkIdIsNaturalNumber();
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
    public function checkIdRequired()
    {
        $params = array();
        $response = $this->deleteJson(route('api.mobile.categories.destroy'), $params, $this->dataheaders);
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
    }
    public function checkIdExists()
    {
        $params = array("id" => 22);
        $response = $this->deleteJson(route('api.mobile.categories.destroy'), $params, $this->dataheaders);
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
    }
    public function checkIdIsIntenger()
    {
        $params = array("id" => 1.1);
        $response = $this->deleteJson(route('api.mobile.categories.destroy'), $params, $this->dataheaders);
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
    }
    public function checkIdIsNumeric()
    {
        $params = array("id" => "aaa");
        $response = $this->deleteJson(route('api.mobile.categories.destroy'), $params, $this->dataheaders);
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
    }
    public function checkIdIsNaturalNumber()
    {
        $params = array("id" => -1);
        $response = $this->deleteJson(route('api.mobile.categories.destroy'), $params, $this->dataheaders);
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
    }
}
