<?php

namespace Tests\Feature\icon;

use App\Models\Icon;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class IconStoreTest extends TestCase
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
        $this->mainApiName = 'api.mobile.icons.store';
        $this->mainModelName = Icon::class;
        $this->mainApiMethod = 'post';
        $this->authUser = $this->SanctumActingAs();

        $this->data = [
            'name' => $this->faker->name,
            'is_favorite' => false,
            'image' => $this->storageS3(),
            'color' => $this->faker->hexColor()
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["icons" => 1, "images" => 1]);



        $this->ResponseAssertJson($response, $this->data);
    }
}
