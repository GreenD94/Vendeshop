<?php

namespace Tests\Feature\order;

use App\Models\address;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\OrderApi;
use Tests\api_models\OrderStatusApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class OrderIndexTest extends TestCase
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
        $this->initdata(OrderApi::index());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'user_id' => User::factory()->create()->id,
            'address_id' => address::factory()->create()->id,
            'billing_address_id' => address::factory()->create()->id,
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["orders" => 1]);
        $this->createdModels = $this->mainModelCreateMany([],   ["orders" => 20], 19);
        $this->data = $this->createdModels[18]->toArray();
        $this->data["data_length"] = 20;
    }
    public function checkindex(): void
    {
        $params = array();
        $response = $this->CallIndexApi(null, $params);
        $this->ResponseAssertJson($response, $this->data);
    }
}
