<?php

namespace Tests\Feature\ad;

use App\Models\Ad;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class AdDestroyTest extends TestCase
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
        $this->checkDestroy();
    }


    public function checkData()
    {
        $this->mainApiName = 'api.mobile.ads.destroy';
        $this->mainModelName = Ad::class;
        $this->SanctumActingAs();
        $this->imageModel = Image::factory()->create(["name" => "test"]);
        $this->storageS3();
        $this->data = [
            'name' => $this->faker->name,
            'is_favorite' => false,
            'image_id' => $this->imageModel->id,
            'color' => $this->faker->hexColor()
        ];

        $this->createdModel = $this->mainModelCreate($this->data, ["ads" => 1, "images" => 1]);
    }

    public function checkDestroy()
    {
        $params = array("id" => $this->createdModel->id);
        $response =  $this->CallDestroyApi(null, $params, ["ads" => 0, "images" => 0]);

        $this->data["id"] = $this->createdModel->id;

        $this->data["image_id"] = $this->imageModel->id;
        $this->data["image_name"] = $this->imageModel->name;
        $this->data["image_url"] = $this->imageModel->url;
        $this->ResponseAssertJson($response, $this->data);
    }
}
