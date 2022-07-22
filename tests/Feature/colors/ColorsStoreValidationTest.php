<?php

namespace Tests\Feature\colors;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class ColorsStoreValidationTest extends TestCase
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

        // $this->CheckFieldIsRequired("address",  $this->data);

    }
    public function checkData()
    {

        $this->initdata(ColorsApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'hex' => $this->faker->hexColor(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["colors" => 1]);
    }
}
