<?php

namespace Tests\Feature\order_status;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\OrderStatusApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class OrderStatusStoreTest extends TestCase
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
        $this->checkStore();
    }
    public function checkData(): void
    {
        $this->initdata(OrderStatusApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name(),
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["order_statuses" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
