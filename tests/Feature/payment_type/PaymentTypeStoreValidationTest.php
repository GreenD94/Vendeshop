<?php

namespace Tests\Feature\payment_type;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\PaymentTypeApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class PaymentTypeStoreValidationTest extends TestCase
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

        $this->CheckFieldIsRequired("name",  $this->data);
    }
    public function checkData()
    {

        $this->initdata(PaymentTypeApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->address(),

        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["payment_types" => 1]);
    }
}
