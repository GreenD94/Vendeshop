<?php

namespace Tests\Feature\address;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class addressStoreValidationTest extends TestCase
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

        $this->CheckFieldIsRequired("address",  $this->data);
        $this->CheckFieldIsRequired("city_id",  $this->data);
        $this->CheckFieldIsRequired("street",  $this->data);
        $this->CheckFieldIsRequired("postal_code",  $this->data);
        $this->CheckFieldIsRequired("deparment",  $this->data);
        $this->CheckFieldIsRequired("phone_number",  $this->data);
    }
    public function checkData()
    {

        $this->initdata(AddressApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'address' => $this->faker->address(),
            'city_id' => 1,
            'city_name' => $this->faker->city(),
            'street' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'deparment' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["addresses" => 1]);
    }
}
