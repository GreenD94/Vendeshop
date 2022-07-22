<?php

namespace Tests\Feature\post;

use App\Models\Post;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PostDestroyTest extends TestCase
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
        $this->mainApiName = 'api.mobile.posts.destroy';
        $this->mainModelName = Post::class;
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

    public function checkDestroy()
    {
        $params = array("id" => $this->createdModel->id);
        $response =  $this->CallDestroyApi(null, $params, ["posts" => 0, "users" => 1, "stocks" => 1]);

        $this->data["id"] = $this->createdModel->id;
        $this->data["user_name"] =  $this->auhUser->first_name . ' ' . $this->auhUser->last_name;
        $this->ResponseAssertJson($response, $this->data);
    }
}
