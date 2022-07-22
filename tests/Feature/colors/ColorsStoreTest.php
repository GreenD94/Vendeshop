<?php

namespace Tests\Feature\colors;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class ColorsStoreTest extends TestCase
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
        $this->checkStore();
    }
    public function checkData(): void
    {
        $this->initdata(ColorsApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'hex' => $this->faker->hexColor(),
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["colors" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
