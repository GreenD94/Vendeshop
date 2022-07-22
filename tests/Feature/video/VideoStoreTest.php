<?php

namespace Tests\Feature\video;

use App\Models\Icon;
use App\Models\Image;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class VideoStoreTest extends TestCase
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
        $this->mainApiName = 'api.mobile.videos.store';
        $this->mainModelName = Video::class;
        $this->mainApiMethod = 'post';
        $this->authUser = $this->SanctumActingAs();

        $this->data = [
            'name' => $this->faker->name,
            'url' =>  $this->faker->url(),
            'is_information' => true
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["videos" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
