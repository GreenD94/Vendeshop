<?php

namespace Tests\Feature\address;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class addressUpdateTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use CheckHelpers2;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->checkData();

        $this->checkUpdateField('address', $this->faker->address(), $this->data,   $this->createdModel, ["addresses" => 1]);
        $this->checkUpdateField('city_id', 2, $this->data,   $this->createdModel, ["addresses" => 1]);
        $this->checkUpdateField('street', $this->faker->address(), $this->data,   $this->createdModel, ["addresses" => 1]);
        $this->checkUpdateField('postal_code', $this->faker->address(), $this->data,   $this->createdModel, ["addresses" => 1]);
        $this->checkUpdateField('deparment', $this->faker->address(), $this->data,   $this->createdModel, ["addresses" => 1]);
        $this->checkUpdateField('phone_number', $this->faker->address(), $this->data,   $this->createdModel, ["addresses" => 1]);
    }
    public function checkData(): void
    {
        $this->initdata(AddressApi::update());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'address' => $this->faker->address(),
            'city_id' => 1,
            'street' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'deparment' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["addresses" => 1]);
        $this->data['id'] = $this->createdModel->id;
    }
}
