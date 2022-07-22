<?php

namespace Tests\Feature\ticket;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\TicketsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class TicketIndexValidationTest extends TestCase
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
        $params = array(
            'page' => 1,
            'limit' => 1
        );

        $this->checkFieldIsIntenger("page", $params);
        $this->checkFieldIsNumeric("page", $params);
        $this->checkFieldIsNaturalNumber("page", $params);



        $this->checkFieldIsIntenger("limit", $params);
        $this->checkFieldIsNumeric("limit", $params);
        $this->checkFieldIsNaturalNumber("limit", $params);

        $this->checkFieldIsIntenger("user_id", $params);
        $this->checkFieldIsNumeric("user_id", $params);
        $this->checkFieldIsNaturalNumber("user_id", $params);
    }
    public function checkData()
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
        $this->mainModelCreateMany([],   ["tickets" => 20], 19);
    }
}
