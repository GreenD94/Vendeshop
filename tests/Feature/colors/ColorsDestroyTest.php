<?php

namespace Tests\Feature\colors;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class ColorsDestroyTest extends TestCase
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
        $this->checkDestroy();
    }


    public function checkData()
    {
        $this->initdata(ColorsApi::destroy());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'hex' => $this->faker->hexColor(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["colors" => 1]);
    }

    public function checkDestroy()
    {
        $params = array("id" => $this->createdModel->id);
        $response =  $this->CallDestroyApi(null, $params, ["colors" => 0]);
        $this->data["id"] = $this->createdModel->id;
        $this->ResponseAssertJson($response, $this->data);
    }
}
