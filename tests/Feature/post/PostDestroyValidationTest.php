<?php

namespace Tests\Feature\post;

use App\Models\Post;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PostDestroyValidationTest extends TestCase
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
        $this->mainApiName = 'api.mobile.posts.destroy';
        $this->mainModelName = Post::class;
        $this->mainApiMethod = "delete";
        $this->auhUser = $this->SanctumActingAs();
        $this->stockModel = Stock::factory()->create();

        $this->data = [
            "body" => $this->faker->word(),
            "is_main" => true,
            "user_id" =>   $this->auhUser->id,
            "stock_id" =>  $this->stockModel->id,
        ];

        $this->createdModel = $this->mainModelCreate($this->data, ["posts" => 1, "users" => 1, "stocks" => 1]);
    }
}
