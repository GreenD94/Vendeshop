<?php

namespace Tests\Feature\order;

use App\Models\address;
use App\Models\PaymentType;
use App\Models\Stock;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\FakeDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\OrderApi;
use Tests\api_models\OrderStatusApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class OrderStoreValidationTest extends TestCase
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

        $this->CheckFieldIsRequired("payment_type_id",  $this->data);
        $this->checkFieldIsExists("payment_type_id", $this->data);
        $this->checkFieldIsIntenger("payment_type_id", $this->data);
        $this->checkFieldIsNumeric("payment_type_id", $this->data);
        $this->checkFieldIsNaturalNumber("payment_type_id", $this->data);

        $this->CheckFieldIsRequired("user_id",  $this->data);
        $this->checkFieldIsExists("user_id", $this->data);
        $this->checkFieldIsIntenger("user_id", $this->data);
        $this->checkFieldIsNumeric("user_id", $this->data);
        $this->checkFieldIsNaturalNumber("user_id", $this->data);



        $this->checkFieldIsExists("address_id", $this->data);
        $this->checkFieldIsIntenger("address_id", $this->data);
        $this->checkFieldIsNumeric("address_id", $this->data);
        $this->checkFieldIsNaturalNumber("address_id", $this->data);



        $this->checkFieldIsExists("billing_address_id", $this->data);
        $this->checkFieldIsIntenger("billing_address_id", $this->data);
        $this->checkFieldIsNumeric("billing_address_id", $this->data);
        $this->checkFieldIsNaturalNumber("billing_address_id", $this->data);



        // $this->CheckFieldIsRequired("stocks",  $this->data);
        // $this->CheckFieldIsArray("stocks",  $this->data);
        // $this->checkFieldIsExists("stocks", $this->data);
        // $this->checkFieldIsIntenger("stocks", $this->data);
        // $this->checkFieldIsNumeric("stocks", $this->data);
        // $this->checkFieldIsNaturalNumber("stocks", $this->data);


        $this->checkFieldIsExists("tickets", $this->data);
        $this->CheckFieldIsArray("tickets",  $this->data);
        $this->checkFieldIsIntenger("tickets", $this->data);
        $this->checkFieldIsNumeric("tickets", $this->data);
        $this->checkFieldIsNaturalNumber("tickets", $this->data);
    }
    public function checkData()
    {
        $this->seed(FakeDataSeeder::class);
        $this->initdata(OrderApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'user_id' => User::factory()->create()->id,
            'address_id' => address::factory()->create()->id,
            'billing_address_id' => address::factory()->create()->id,
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["orders" => 1]);
        $this->data['payment_type_id'] = PaymentType::find(1)->id;
        $this->data['stocks'] = Stock::factory()->count(10)->create()->map(function ($item, $key) {
            return collect([
                "id" => $item->id,
                "amount" => 1,
                "color_id" => $item->color_id,
                "size_id" => $item->size_id
            ]);
        });
        $this->data['tickets'] = Ticket::factory()->count(10)->create()->modelKeys();
    }
}
