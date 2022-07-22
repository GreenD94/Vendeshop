<?php

namespace Tests\Feature\order;

use App\Models\address;
use App\Models\PaymentType;
use App\Models\Stock;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\FakeDataSeeder;
use Database\Seeders\MasterUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\OrderApi;
use Tests\api_models\OrderStatusApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class OrderStoreTest extends TestCase
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

        $this->seed(MasterUserSeeder::class);
        $this->seed(FakeDataSeeder::class);
        $this->initdata(OrderApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'user_id' => $this->auhUser->id,
            'address_id' => address::factory()->create()->id,
            'billing_address_id' => address::factory()->create()->id,
            'payment_type_id' => PaymentType::where('name', 'payu')->first()->id,
            'stocks' => Stock::factory()->count(10)->create()->map(function ($item, $key) {
                return collect([
                    "id" => $item->id,
                    "amount" => 1,
                    "color_id" => $item->color_id,
                    "size_id" => $item->size_id
                ]);
            }),
            'tickets' => Ticket::factory()->count(2)->create()->modelKeys(),
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["orders" => 1], 200);

        $this->ResponseAssertJson($response, $this->data);
    }
}
