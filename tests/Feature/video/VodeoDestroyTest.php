<?php

namespace Tests\Feature\video;

use App\Models\Icon;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class VodeoDestroyTest extends TestCase
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
        $this->mainApiName = 'api.mobile.videos.destroy';
        $this->mainModelName = Video::class;
        $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name,
            'url' => $this->faker->url(),
            'is_information' => true
        ];

        $this->createdModel = $this->mainModelCreate($this->data, ["videos" => 1]);
    }

    public function checkDestroy()
    {
        $params = array("id" => $this->createdModel->id);
        $response =  $this->CallDestroyApi(null, $params, ["videos" => 0]);

        $this->data["id"] = $this->createdModel->id;

        $this->ResponseAssertJson($response, $this->data);
    }
}
