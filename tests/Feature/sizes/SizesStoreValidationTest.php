<?php

namespace Tests\Feature\sizes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\api_models\SizesApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class SizesStoreValidationTest extends TestCase
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

        $this->initdata(SizesApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'size' => $this->faker->hexColor(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["sizes" => 1]);
    }
}
