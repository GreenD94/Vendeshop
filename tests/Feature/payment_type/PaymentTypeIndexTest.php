<?php

namespace Tests\Feature\payment_type;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\PaymentTypeApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class PaymentTypeIndexTest extends TestCase
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
        $this->initdata(PaymentTypeApi::index());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["payment_types" => 1]);
        $this->createdModels = $this->mainModelCreateMany([],   ["payment_types" => 20], 19);
        $this->data = $this->createdModels[18]->toArray();
        $this->data["data_length"] = 20;
    }
    public function checkindex(): void
    {
        $params = array('page' => 1);
        $response = $this->CallIndexApi(null, $params);

        $this->ResponseAssertJson($response, $this->data);
    }
}
