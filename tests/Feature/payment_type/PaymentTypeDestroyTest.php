<?php

namespace Tests\Feature\payment_type;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\PaymentTypeApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class PaymentTypeDestroyTest extends TestCase
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
        $this->checkDestroy();
    }


    public function checkData()
    {
        $this->initdata(PaymentTypeApi::destroy());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["payment_types" => 1]);
    }

    public function checkDestroy()
    {
        $params = array("id" => $this->createdModel->id);
        $response =  $this->CallDestroyApi(null, $params, ["payment_types" => 0]);
        $this->data["id"] = $this->createdModel->id;
        $this->ResponseAssertJson($response, $this->data);
    }
}
