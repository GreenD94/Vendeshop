<?php

namespace Tests\Feature\background;

use App\Models\Background;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class BackgroundStoreValidationTest extends TestCase
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



        $this->CheckFieldIsRequired("color",  $this->data);
        $this->CheckFieldIsRequired("is_favorite",  $this->data);
        $this->CheckFieldIsBoolean("is_favorite",  $this->data);
        $this->CheckFieldIsImage("image",  $this->data);
    }
    public function checkData()
    {

        $this->mainApiName = 'api.mobile.backgrounds.store';
        $this->mainModelName = Background::class;
        $this->mainApiMethod = 'post';
        $this->authUser = $this->SanctumActingAs();
        $this->data = [

            'is_favorite' => false,
            'image' => $this->storageS3(),
            'color' => $this->faker->hexColor()
        ];
    }
}
