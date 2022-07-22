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

class OrderUpdateTest extends TestCase
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

        $this->checkUpdateField('order_status_id', OrderStatus::find(2)->id, $this->data,   $this->createdModel, ["orders" => 1]);
    }
    public function checkData(): void
    {
        $this->seed(FakeDataSeeder::class);
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
