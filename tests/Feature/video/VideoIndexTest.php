<?php

namespace Tests\Feature\video;

use App\Models\Icon;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class VideoIndexTest extends TestCase
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
        $this->mainApiName = 'api.mobile.videos.index';
        $this->mainModelName = Video::class;
        $this->mainApiMethod = 'get';
        $this->authUser = $this->SanctumActingAs();
        $this->storageS3();

        $this->data = [
            'name' => $this->faker->name,
            'url' =>  $this->faker->url(),
            'is_information' => true
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["videos" => 1]);
        $this->createdModels = $this->mainModelCreateMany([], ["videos" => 20], 19);
        $this->data['name'] = $this->createdModels[18]->name;
        $this->data['url'] = $this->createdModels[18]->url;
        $this->data['is_information'] = $this->createdModels[18]->is_information;
    }
    public function checkindex(): void
    {

        $response = $this->CallIndexApi(null);


        $this->data["id"] = $this->createdModels[18]->id;
        $this->data["data_length"] = 20;

        $this->ResponseAssertJson($response, $this->data);
    }
}
