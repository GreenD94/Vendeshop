<?php

namespace Tests\Feature\payment_type;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\PaymentTypeApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class PaymentTypeUpdateTest extends TestCase
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

        $this->checkUpdateField('name', $this->faker->name(), $this->data,   $this->createdModel, ["payment_types" => 1]);
    }
    public function checkData(): void
    {
        $this->initdata(PaymentTypeApi::update());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name(),

        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["payment_types" => 1]);
        $this->data['id'] = $this->createdModel->id;
    }
}
