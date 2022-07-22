<?php

namespace Tests\Feature\post;

use App\Models\Post;
use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PostIndexValidationTest extends TestCase
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
        $params = array(
            'page' => 1,
            "id" => $this->createdModel->id,
            'is_main' => true,
            'stock_id' =>  $this->data['stock_id'],
            'with_replies' => true,
            'limit' => 1,
        );

        $this->CheckFieldIsRequired("page", $params);
        $this->checkFieldIsIntenger("page", $params);
        $this->checkFieldIsNumeric("page", $params);
        $this->checkFieldIsNaturalNumber("page", $params);

        // $this->checkFieldIsExists("id", $params);
        // $this->checkFieldIsIntenger("id", $params);
        // $this->checkFieldIsNumeric("id", $params);
        // $this->checkFieldIsNaturalNumber("id", $params);

        // $this->CheckFieldIsBoolean("is_main", $params);


        // $this->checkFieldIsExists("stock_id", $params);
        // $this->checkFieldIsIntenger("stock_id", $params);
        // $this->checkFieldIsNumeric("stock_id", $params);
        // $this->checkFieldIsNaturalNumber("stock_id", $params);

        // $this->CheckFieldIsBoolean("with_replies", $params);

        $this->checkFieldIsIntenger("limit", $params);
        $this->checkFieldIsNumeric("limit", $params);
        $this->checkFieldIsNaturalNumber("limit", $params);
    }
    public function checkData()
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

        $this->createdModel = $this->mainModelCreate($this->data, ["posts" => 1, "users" => 1, "stocks" => 1]);
    }
}
