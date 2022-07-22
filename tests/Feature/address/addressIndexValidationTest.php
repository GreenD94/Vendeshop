<?php

namespace Tests\Feature\address;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class addressIndexValidationTest extends TestCase
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
        $params = array(
            'page' => 1,
            'limit' => 1
        );

        $this->checkFieldIsIntenger("page", $params);
        $this->checkFieldIsNumeric("page", $params);
        $this->checkFieldIsNaturalNumber("page", $params);



        $this->checkFieldIsIntenger("limit", $params);
        $this->checkFieldIsNumeric("limit", $params);
        $this->checkFieldIsNaturalNumber("limit", $params);
    }
    public function checkData()
    {
        $this->initdata(AddressApi::index());
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
        $this->mainModelCreateMany([],   ["addresses" => 20], 19);
    }
}
