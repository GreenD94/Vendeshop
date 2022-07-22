<?php

namespace Tests\Feature\colors;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class ColorsUpdateTest extends TestCase
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

        $this->checkUpdateField('hex', $this->faker->hexColor(), $this->data,   $this->createdModel, ["colors" => 1]);
    }
    public function checkData(): void
    {
        $this->initdata(ColorsApi::update());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'hex' => $this->faker->hexColor(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["colors" => 1]);
        $this->data['id'] = $this->createdModel->id;
    }
}
