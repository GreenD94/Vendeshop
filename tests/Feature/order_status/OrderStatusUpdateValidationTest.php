<?php

namespace Tests\Feature\order_status;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\OrderStatusApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class OrderStatusUpdateValidationTest extends TestCase
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


        $this->CheckFieldIsRequired("id", $this->data);
        $this->checkFieldIsExists("id", $this->data);

        $this->checkFieldIsNumeric("id", $this->data);
        $this->checkFieldIsNaturalNumber("id", $this->data);
    }
    public function checkData()
    {
        $this->initdata(OrderStatusApi::update());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["order_statuses" => 1]);
        $this->data['id'] = $this->createdModel->id;
    }
}
