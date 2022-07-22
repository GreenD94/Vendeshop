<?php

namespace Tests\Feature\address;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class addressDestroyValidationTest extends TestCase
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
        $params = array("id" => $this->createdModel->id);
        $this->CheckFieldIsRequired("id", $params);
        $this->checkFieldIsExists("id", $params);
        $this->checkFieldIsIntenger("id", $params);
        $this->checkFieldIsNumeric("id", $params);
        $this->checkFieldIsNaturalNumber("id", $params);
    }
    public function checkData()
    {
        $this->initdata(AddressApi::destroy());
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
    }
}
