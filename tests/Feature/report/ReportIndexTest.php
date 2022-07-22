<?php

namespace Tests\Feature\report;

use App\Models\Order;
use App\Models\OrderStatus;
use Database\Seeders\FakeDataSeeder;
use Database\Seeders\MasterUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ReportApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class ReportIndexTest extends TestCase
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
        $this->seed(MasterUserSeeder::class);
        $this->seed(FakeDataSeeder::class);
        $this->initdata(ReportApi::index());
        $this->auhUser = $this->SanctumActingAs();

        $orders = Order::factory()->count(20)->create();
        $status_finalizado = OrderStatus::find(2);
        $status_espera = OrderStatus::find(1);
        $orders->each(function ($item, $key) use ($status_finalizado, $status_espera) {
            $status = (($key % 2) == 0) ?  $status_finalizado : $status_espera;
            $item->addStatusLog($status);
        });
       
    }
    public function checkindex(): void
    {
        $params = array();
        $response = $this->CallIndexApi(null, $params);

        $this->ResponseAssertJson($response, $this->data);
    }
}
