<?php

namespace Tests\Feature\ticket;

use App\Models\Ticket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\TicketsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class TicketStoreTest extends TestCase
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
        $this->initdata(TicketsApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'user_id' => User::factory()->create()->id,
            'value' => $this->faker->randomNumber(),
            'expiration_time' => Carbon::now(),
            'is_used' => false,
            'is_active' => true,
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["tickets" => 1]);

        $this->ResponseAssertJson($response, $this->data);
    }
}
