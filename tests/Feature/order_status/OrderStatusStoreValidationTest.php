<?php

namespace Tests\Feature\order_status;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\OrderStatusApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class OrderStatusStoreValidationTest extends TestCase
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
        $this->CheckFieldIsRequired("name",  $this->data);
    }
    public function checkData()
    {

        $this->initdata(OrderStatusApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["order_statuses" => 1]);
    }
}
