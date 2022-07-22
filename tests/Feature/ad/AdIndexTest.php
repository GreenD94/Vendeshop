<?php

namespace Tests\Feature\ad;

use App\Models\Ad;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class AdIndexTest extends TestCase
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
        $this->checkindex();
    }
    public function checkData(): void
    {
        $this->mainApiName = 'api.mobile.ads.index';
        $this->mainModelName = Ad::class;
        $this->mainApiMethod = 'get';
        $this->authUser = $this->SanctumActingAs();
        $this->storageS3();
        $this->imageModel = Image::factory()->create();
        $this->data = [
            'name' => $this->faker->name,
            'is_favorite' => false,
            'image_id' => $this->imageModel->id,
            'color' => $this->faker->hexColor()
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["ads" => 1]);
        $this->createdModels =  $this->mainModelCreateMany([], ["ads" => 20], 19);
        $this->data = $this->createdModels[18]->toArray();
        $this->data["image_id"] =  $this->createdModels[18]->image->id;
        $this->data["image_name"] = $this->createdModels[18]->image->name;
        $this->data["image_url"] = $this->createdModels[18]->image->url;
    }
    public function checkindex(): void
    {

        $response = $this->CallIndexApi(null);


        $this->data["data_length"] = 20;

        $this->ResponseAssertJson($response, $this->data);
    }
}
