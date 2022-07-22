<?php

namespace Tests\Feature\payment_type;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\PaymentTypeApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class PaymentTypeIndexValidationTest extends TestCase
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
        $this->initdata(PaymentTypeApi::index());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->address(),

        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["payment_types" => 1]);
        $this->mainModelCreateMany([],   ["payment_types" => 20], 19);
    }
}
