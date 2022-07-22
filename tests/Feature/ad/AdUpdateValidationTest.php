<?php

namespace Tests\Feature\ad;

use App\Models\Ad;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class AdUpdateValidationTest extends TestCase
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


        $this->CheckFieldIsBoolean("is_favorite",  $this->data);
        $this->CheckFieldIsImage("image",  $this->data);
        $this->CheckFieldIsRequired("id",  $this->data);
        $this->checkFieldIsExists("id",  $this->data);
        $this->checkFieldIsIntenger("id",  $this->data);
        $this->checkFieldIsNumeric("id",  $this->data);
        $this->checkFieldIsNaturalNumber("id",  $this->data);
    }
    public function checkData()
    {
        $this->mainApiName = 'api.mobile.ads.update';
        $this->mainModelName = Ad::class;
        $this->mainApiMethod = 'put';
        $this->authUser = $this->SanctumActingAs();
        $this->imageModel = Image::factory()->create();
        $this->data = [
            'name' => $this->faker->name,
            'is_favorite' => false,
            'image_id' => $this->imageModel->id,
            'color' => $this->faker->hexColor()
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["ads" => 1]);
        $this->data['id'] = $this->createdModel->id;
        $this->data["image"] = $this->storageS3();
    }
}
