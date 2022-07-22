<?php

namespace Tests\Feature\sizes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\api_models\SizesApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class SizesIndexTest extends TestCase
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
        $this->initdata(SizesApi::index());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'size' => $this->faker->hexColor(),
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["sizes" => 1]);
        $this->createdModels = $this->mainModelCreateMany([],   ["sizes" => 20], 19);
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
