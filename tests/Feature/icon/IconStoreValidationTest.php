<?php

namespace Tests\Feature\icon;

use App\Models\Icon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class IconStoreValidationTest extends TestCase
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
        $this->CheckFieldIsRequired("color",  $this->data);
        $this->CheckFieldIsRequired("is_favorite",  $this->data);
        $this->CheckFieldIsBoolean("is_favorite",  $this->data);
        $this->CheckFieldIsImage("image",  $this->data);
    }
    public function checkData()
    {

        $this->mainApiName = 'api.mobile.icons.store';
        $this->mainModelName = Icon::class;
        $this->mainApiMethod = 'post';
        $this->authUser = $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name,
            'is_favorite' => false,
            'image' => $this->storageS3(),
            'color' => $this->faker->hexColor()
        ];
    }
}
