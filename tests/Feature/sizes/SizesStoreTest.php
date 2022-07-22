<?php

namespace Tests\Feature\sizes;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\api_models\AddressApi;
use Tests\api_models\ColorsApi;
use Tests\api_models\SizesApi;
use Tests\CheckHelpers2;
use Tests\TestCase;

class SizesStoreTest extends TestCase
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
        $this->initdata(SizesApi::store());
        $this->auhUser = $this->SanctumActingAs();
        $this->data = [
            'size' => $this->faker->hexColor(),
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["sizes" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
