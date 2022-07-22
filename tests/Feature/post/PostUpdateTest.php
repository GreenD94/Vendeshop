<?php

namespace Tests\Feature\post;

use App\Models\Post;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PostUpdateTest extends TestCase
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
        $this->checkUpdateBody();
        $this->checkUpdateIsMain();
        $this->checkUpdateUserId();
        $this->checkUpdateStockId();
        //$this->checkUpdateImage();
    }
    public function checkData(): void
    {
        $this->mainApiName = 'api.mobile.posts.update';
        $this->mainModelName = Post::class;
        $this->mainApiMethod = 'put';
        $this->auhUser = $this->SanctumActingAs();
        $this->stockModel = Stock::factory()->create();

        $this->data = [
            "body" => $this->faker->word(),
            "is_main" => true,
            "user_id" =>   $this->auhUser->id,
            "stock_id" =>  $this->stockModel->id,
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["posts" => 1, "users" => 1, "stocks" => 1]);
        $this->data['id'] = $this->createdModel->id;
        $this->data["user_name"] =  $this->auhUser->first_name . ' ' . $this->auhUser->last_name;
    }
    public function checkUpdateBody()
    {
        $this->data["body"] =  $this->faker->name;
        $params = array('id' => $this->createdModel->id, "body" => $this->data["body"]);
        $response = $this->CallUpdateApi(null,  $params,  ["posts" => 1, "users" => 1, "stocks" => 1]);

        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateIsMain()
    {

        $this->data["is_main"] = false;
        $params = array('id' => $this->createdModel->id, "is_main" => $this->data["is_main"]);
        $response = $this->CallUpdateApi(null,  $params, ["posts" => 1, "users" => 1, "stocks" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateUserId()
    {
        $userModel = User::factory()->create();
        $this->data["user_id"] =  $userModel->id;
        $this->data["user_name"] =  $userModel->first_name . ' ' . $userModel->last_name;
        $params = array('id' => $this->createdModel->id, "user_id" => $this->data["user_id"]);
        $response = $this->CallUpdateApi(null,  $params, ["posts" => 1, "users" => 2, "stocks" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateStockId()
    {
        $stockModel = Stock::factory()->create();
        $this->data["stock_id"] =  $stockModel->id;

        $params = array('id' => $this->createdModel->id, "stock_id" => $this->data["stock_id"]);
        $response = $this->CallUpdateApi(null,  $params, ["posts" => 1, "users" => 2, "stocks" => 2]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
