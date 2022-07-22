<?php

namespace Tests\Feature\order_status;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\OrderStatusApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class OrderStatusIndexTest extends TestCase
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
        $this->initdata(OrderStatusApi::index());
        $this->auhUser = $this->SanctumActingAs();

        $this->data = [
            'name' => $this->faker->name(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["order_statuses" => 1]);
        $this->createdModels = $this->mainModelCreateMany([],   ["order_statuses" => 20], 19);
    }
    public function checkindex(): void
    {
        $params = array('page' => 1);
        $response = $this->CallIndexApi(null, $params);
        $this->data["data_length"] = 20;
        $this->data["id"] = $this->createdModel->id;
        $this->ResponseAssertJson($response, $this->data);
    }
}
