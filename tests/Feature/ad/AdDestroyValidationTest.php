<?php

namespace Tests\Feature\ad;

use App\Models\Ad;
use App\Models\Image;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class AdDestroyValidationTest extends TestCase
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
        $params = array("id" => $this->createdModel->id);
        $this->CheckFieldIsRequired("id", $params);
        $this->checkFieldIsExists("id", $params);
        $this->checkFieldIsIntenger("id", $params);
        $this->checkFieldIsNumeric("id", $params);
        $this->checkFieldIsNaturalNumber("id", $params);
    }
    public function checkData()
    {
        $this->mainApiName = 'api.mobile.ads.destroy';
        $this->mainModelName = Ad::class;
        $this->mainApiMethod = "delete";
        $this->SanctumActingAs();
        $this->imageModel = Image::factory()->create();
        $this->data = [
            'name' => $this->faker->name,
            'is_favorite' => false,
            'image_id' => $this->imageModel->id,
            'color' => $this->faker->hexColor()
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["ads" => 1]);
    }
}
