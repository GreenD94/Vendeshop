<?php

namespace Tests\Feature\order;

use App\Models\address;
use App\Models\OrderStatus;
use App\Models\PaymentType;
use App\Models\Stock;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\FakeDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\OrderApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class OrderUpdateValidationTest extends TestCase
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
        $this->seed(FakeDataSeeder::class);
        $this->checkData();

        $params = array("id" => $this->createdModel->id);
        $this->data["order_status_id"] = OrderStatus::find(2)->id;
        $this->CheckFieldIsRequired("id", $this->data);
        $this->checkFieldIsExists("id", $this->data);
        $this->checkFieldIsIntenger("id", $this->data);
        $this->checkFieldIsNumeric("id", $this->data);
        $this->checkFieldIsNaturalNumber("id", $this->data);



        $this->checkFieldIsExists("order_status_id", $this->data);
        $this->checkFieldIsExists("order_status_id", $this->data);
        $this->checkFieldIsIntenger("order_status_id", $this->data);
        $this->checkFieldIsNumeric("order_status_id", $this->data);
        $this->CheckFieldIsRequired("order_status_id", $this->data);
    }
    public function checkData()
    {
        $this->initdata(OrderApi::update());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'user_id' => $this->auhUser->id,
            'address_id' => address::factory()->create()->id,
            'billing_address_id' => address::factory()->create()->id,

        ];

        $this->createdModel = $this->mainModelCreate($this->data, ["orders" => 1]);
        $this->data['payment_type_id'] = PaymentType::find(1)->id;
        $this->data['stocks'] = Stock::factory()->count(10)->create();
        $this->data['tickets'] = Ticket::factory()->count(10)->create();
        $this->data['id'] = $this->createdModel->id;
    }
}
