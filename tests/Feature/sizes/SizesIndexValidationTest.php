<?php

namespace Tests\Feature\sizes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\api_models\SizesApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class SizesIndexValidationTest extends TestCase
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
        $this->initdata(SizesApi::index());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'size' => $this->faker->hexColor(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["sizes" => 1]);
        $this->mainModelCreateMany([],   ["sizes" => 20], 19);
    }
}
