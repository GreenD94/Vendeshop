<?php

namespace Tests\Feature\video;

use App\Models\Icon;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class VideoUpdateValidationTest extends TestCase
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

        $this->CheckFieldIsBoolean("is_information",  $this->data);

        $this->CheckFieldIsRequired("id",  $this->data);
        $this->checkFieldIsExists("id",  $this->data);
        $this->checkFieldIsIntenger("id",  $this->data);
        $this->checkFieldIsNumeric("id",  $this->data);
        $this->checkFieldIsNaturalNumber("id",  $this->data);
    }
    public function checkData()
    {
        $this->mainApiName = 'api.mobile.videos.update';
        $this->mainModelName = Video::class;
        $this->mainApiMethod = 'put';
        $this->authUser = $this->SanctumActingAs();

        $this->data = [
            'name' => $this->faker->name,
            'url' =>  $this->faker->url(),
            'is_information' => true
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["videos" => 1]);
        $this->data['id'] = $this->createdModel->id;
    }
}
