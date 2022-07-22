<?php

namespace Tests\Feature\ticket;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\TicketsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class TicketStoreValidationTest extends TestCase
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


        $this->CheckFieldIsRequired("user_id",  $this->data);
        $this->checkFieldIsExists("user_id",  $this->data);
        $this->checkFieldIsIntenger("user_id",  $this->data);
        $this->checkFieldIsNumeric("user_id",  $this->data);
        $this->checkFieldIsNaturalNumber("user_id",  $this->data);

        $this->CheckFieldIsRequired("value",  $this->data);
        $this->CheckFieldIsRequired("expiration_time",  $this->data);
        $this->CheckFieldIsDate("expiration_time",  $this->data);

        // $this->CheckFieldIsRequired("is_used",  $this->data);
        // $this->CheckFieldIsBoolean("is_used",  $this->data);

        // $this->CheckFieldIsRequired("is_active",  $this->data);
        // $this->CheckFieldIsBoolean("is_active",  $this->data);
    }
    public function checkData()
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
        $this->createdModel = $this->mainModelCreate($this->data, ["tickets" => 1]);
    }
}
