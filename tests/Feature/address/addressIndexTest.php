<?php

namespace Tests\Feature\address;

use Database\Seeders\MasterUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class addressIndexTest extends TestCase
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
        $this->checkindex();
    }
    public function checkData(): void
    {
        $this->seed(MasterUserSeeder::class);
        $this->initdata(AddressApi::index());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'address' => $this->faker->address(),
            'city_id' => 1,
            'street' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'deparment' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'city_name' => $this->faker->city(),
        ];
        // $this->createdModel = $this->mainModelCreate($this->data, ["addresses" => 1]);
        // $this->createdModels = $this->mainModelCreateMany([],   ["addresses" => 20], 19);
        // $this->data = $this->createdModels[18]->toArray();
        $this->data["data_length"] = 5;
    }
    public function checkindex(): void
    {
        $params = array();
        $response = $this->CallIndexApi(null, $params);

        $this->ResponseAssertJson($response, $this->data);
    }
}
