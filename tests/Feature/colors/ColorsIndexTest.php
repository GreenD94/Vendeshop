<?php

namespace Tests\Feature\colors;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class ColorsIndexTest extends TestCase
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
        $this->initdata(ColorsApi::index());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'hex' => $this->faker->hexColor(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["colors" => 1]);
        $this->createdModels = $this->mainModelCreateMany([],   ["colors" => 20], 19);
        $this->data = $this->createdModels[18]->toArray();
        $this->data["data_length"] = 20;
    }
    public function checkindex(): void
    {
        $params = array();
        $response = $this->CallIndexApi(null, $params);

        $this->ResponseAssertJson($response, $this->data);
    }
}
