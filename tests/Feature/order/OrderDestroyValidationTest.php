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

class OrderDestroyValidationTest extends TestCase
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
        $this->initdata(OrderApi::destroy());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'user_id' => User::factory()->create()->id,
            'address_id' => address::factory()->create()->id,
            'billing_address_id' => address::factory()->create()->id,
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["orders" => 1]);
    }
}
