<?php

namespace Tests\Feature\payment_type;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\PaymentTypeApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class PaymentTypeUpdateValidationTest extends TestCase
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
        $this->initdata(PaymentTypeApi::update());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name(),

        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["payment_types" => 1]);
        $this->data['id'] = $this->createdModel->id;
    }
}
