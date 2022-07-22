<?php

namespace Tests\Feature\background;

use App\Models\Background;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class BackgroundUpdateTest extends TestCase
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

        $this->checkUpdateImageId();
        $this->checkUpdateIsFavorite();
        $this->checkUpdateColor();
        //$this->checkUpdateImage();
    }
    public function checkData(): void
    {
        $this->mainApiName = 'api.mobile.backgrounds.update';
        $this->mainModelName = Background::class;
        $this->mainApiMethod = 'put';
        $this->authUser = $this->SanctumActingAs();
        $this->storageS3();
        $this->imageModel = Image::factory()->create();
        $this->data = [

            'is_favorite' => false,
            'image_id' => $this->imageModel->id,
            'color' => $this->faker->name()
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["backgrounds" => 1]);
        $this->data['id'] = $this->createdModel->id;
        $this->data["image_id"] = $this->imageModel->id;
        $this->data["image_name"] = $this->imageModel->name;
        $this->data["image_url"] = $this->imageModel->url;
    }

    public function checkUpdateImageId()
    {
        $imageModel = Image::factory()->create();
        $this->data["image_id"] =  $imageModel->id;
        $this->data["image_name"] = $imageModel->name;
        $this->data["image_url"] = $imageModel->url;
        $params = array('id' => $this->createdModel->id, "image_id" => $this->data["image_id"]);
        $response = $this->CallUpdateApi(null,  $params, ["backgrounds" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateIsFavorite()
    {
        $this->data["is_favorite"] =  false;
        $params = array('id' => $this->createdModel->id, "is_favorite" => $this->data["is_favorite"]);
        $response = $this->CallUpdateApi(null,  $params, ["backgrounds" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateColor()
    {
        $this->data["color"] =  "aaaaaa";
        $params = array('id' => $this->createdModel->id, "color" => $this->data["color"]);
        $response = $this->CallUpdateApi(null,  $params, ["backgrounds" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateImage()
    {
        $this->data["color"] =  false;
        $params = array('id' => $this->createdModel->id, "color" => $this->data["color"]);
        $response = $this->CallUpdateApi(null,  $params, ["backgrounds" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
