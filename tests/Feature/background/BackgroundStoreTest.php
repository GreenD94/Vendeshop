<?php

namespace Tests\Feature\background;

use App\Models\Background;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class BackgroundStoreTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use CheckHelpers;
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
        $this->mainApiName = 'api.mobile.backgrounds.store';
        $this->mainModelName = Background::class;
        $this->mainApiMethod = 'post';
        $this->authUser = $this->SanctumActingAs();

        $this->data = [

            'is_favorite' => false,
            'image' => $this->storageS3(),
            'color' => $this->faker->hexColor()
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["backgrounds" => 1, "images" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
