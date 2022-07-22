<?php

namespace Tests\Feature\post;

use App\Models\Post;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class PostIndexTest extends TestCase
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
        // $this->checkIsMain();
        // $this->checkStockId();
        // $this->checkId();
        // $this->checkReplies();
        // $this->checkLimit();
    }
    public function checkData(): void
    {
        $this->mainApiName = 'api.mobile.posts.index';
        $this->mainModelName = Post::class;
        $this->mainApiMethod = 'get';
        $this->auhUser = $this->SanctumActingAs();
        $this->stockModel = Stock::factory()->create();

        $this->data = [
            "body" => $this->faker->word(),
            "is_main" => true,
            "user_id" =>   $this->auhUser->id,
            "stock_id" =>  $this->stockModel->id,
        ];
        $adminUser = User::factory()->create([
            'first_name' => "admin",
            'last_name' => "admin",
            'email' => "admin@admin.com"
        ]);
        $posts = Post::factory()->count(3)->create(["is_main" => true])->each(function ($posts, $key) use ($adminUser) {
            $repliesId = Post::factory()->count(3)->state(new Sequence(
                ['user_id' =>  $adminUser->id, 'stock_id' => $posts->stock_id],
                ['user_id' => $posts->user_id, 'stock_id' => $posts->stock_id],
            ))->create()->modelKeys();
            $posts->replies()->attach($repliesId);
        });


        // $this->createdModel = $this->mainModelCreate($this->data, ["posts" => 1, "users" => 1, "stocks" => 1]);
        // $this->createdModels =  $this->mainModelCreateMany([],  ["posts" => 20, "users" => 20, "stocks" => 20], 19);
        // $this->data = $this->createdModels[18]->toArray();
    }
    public function checkindex(): void
    {
        $params = array('page' => 1);
        $response = $this->CallIndexApi(null, $params);



        $this->data["data_length"] = 3;
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkIsMain(): void
    {
        $params = array('page' => 1, 'is_main' => true);
        $response = $this->CallIndexApi(null, $params);


        $data["user_name"] =   $this->createdModel->user->first_name . ' ' .  $this->createdModel->user->last_name;

        $data["data_length"] = 1;
        $this->ResponseAssertJson($response, $data);
    }
    public function checkStockId(): void
    {
        $params = array('page' => 1, 'stock_id' =>  $this->createdModels[18]->id);
        $response = $this->CallIndexApi(null, $params);


        $this->data["user_name"] =  $this->createdModels[18]->user->first_name . ' ' . $this->createdModels[18]->user->last_name;
        $this->data["data_length"] = 1;
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkId(): void
    {
        $params = array('page' => 1, 'id' =>   $this->createdModels[18]->id);
        $response = $this->CallIndexApi(null, $params);


        $this->data["user_name"] =   $this->createdModels[18]->user->first_name . ' ' .  $this->createdModels[18]->user->last_name;
        $this->data["data_length"] = 1;
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkReplies(): void
    {
        $params = array('page' => 1, 'with_replies' => true);
        $response = $this->CallIndexApi(null, $params);


        $this->data["user_name"] =   $this->createdModels[18]->user->first_name . ' ' .  $this->createdModels[18]->user->last_name;
        $this->data["data_length"] = 5;
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkLimit(): void
    {
        $params = array('page' => 1, 'limit' => 1);
        $response = $this->CallIndexApi(null, $params);


        $this->data["user_name"] =   $this->createdModels[18]->user->first_name . ' ' .  $this->createdModels[18]->user->last_name;
        $this->data["data_length"] = 1;
        $this->ResponseAssertJson($response, $this->data);
    }
}
