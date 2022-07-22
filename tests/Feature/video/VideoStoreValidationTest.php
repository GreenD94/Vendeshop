<?php

namespace Tests\Feature\video;

use App\Models\Icon;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class VideoStoreValidationTest extends TestCase
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


        $this->CheckFieldIsRequired("name",  $this->data);
        $this->CheckFieldIsRequired("url",  $this->data);

        $this->CheckFieldIsBoolean("is_information",  $this->data);
    }
    public function checkData()
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
}
