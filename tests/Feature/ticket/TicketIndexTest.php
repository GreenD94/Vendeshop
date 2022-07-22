<?php

namespace Tests\Feature\ticket;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\TicketsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class TicketIndexTest extends TestCase
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
        $this->checkUserIdindex();
    }
    public function checkData(): void
    {
        $this->initdata(TicketsApi::index());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'user_id' => User::factory()->create()->id,
            'value' => $this->faker->randomNumber(),
            'expiration_time' => Carbon::now(),
            'is_used' => false,
            'is_active' => true,
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["tickets" => 1]);
        $this->createdModels = $this->mainModelCreateMany([],   ["tickets" => 20], 19);
        $this->data = $this->createdModels[18]->toArray();
        $this->data["data_length"] = 20;
    }
    public function checkindex(): void
    {
        $params = array();
        $response = $this->CallIndexApi(null, $params);

        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUserIdindex(): void
    {
        $params = array('user_id' => $this->data['user_id']);
        $response = $this->CallIndexApi(null, $params);
        $this->data["data_length"] = 1;
        $this->ResponseAssertJson($response, $this->data);
    }
}
