<?php

namespace Tests\Feature\video;

use App\Models\Icon;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class VideoUpdateTest extends TestCase
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
        $this->checkUpdateName();
        $this->checkUpdateUrl();
        $this->checkUpdateIsInformation();
    }
    public function checkData(): void
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
    public function checkUpdateName()
    {
        $this->data["name"] =  $this->faker->name;
        $params = array('id' => $this->createdModel->id, "name" => $this->data["name"]);
        $response = $this->CallUpdateApi(null,  $params, ["videos" => 1]);

        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateUrl()
    {
        $this->data["url"] =  $this->faker->url();
        $params = array('id' => $this->createdModel->id, "url" => $this->data["url"]);
        $response = $this->CallUpdateApi(null,  $params, ["videos" => 1]);

        $this->ResponseAssertJson($response, $this->data);
    }

    public function checkUpdateIsInformation()
    {
        $this->data["is_information"] =  false;
        $params = array('id' => $this->createdModel->id, "is_information" => $this->data["is_information"]);
        $response = $this->CallUpdateApi(null,  $params, ["videos" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
