<?php

namespace Tests\Feature\ticket;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\TicketsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class TicketUpdateTest extends TestCase
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

        $this->checkUpdateField('user_id', User::factory()->create()->id, $this->data,   $this->createdModel, ["tickets" => 1]);
        $this->checkUpdateField('value',  $this->faker->randomNumber(), $this->data,   $this->createdModel, ["tickets" => 1]);
        $this->checkUpdateField('expiration_time', Carbon::now(), $this->data,   $this->createdModel, ["tickets" => 1]);
        $this->checkUpdateField('is_used', true,    $this->data,  $this->createdModel, ["tickets" => 1]);
        $this->checkUpdateField('is_active', false,    $this->data,  $this->createdModel, ["tickets" => 1]);
    }
    public function checkData(): void
    {
        $this->initdata(TicketsApi::update());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'user_id' => User::factory()->create()->id,
            'value' => $this->faker->randomNumber(),
            'expiration_time' => Carbon::now(),
            'is_used' => false,
            'is_active' => true,
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["tickets" => 1]);
        $this->data['id'] = $this->createdModel->id;
    }
}
