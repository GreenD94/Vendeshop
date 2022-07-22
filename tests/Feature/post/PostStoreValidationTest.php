<?php

namespace Tests\Feature\post;

use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PostStoreValidationTest extends TestCase
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

        $this->CheckFieldIsRequired("body",  $this->data);

        $this->checkFieldIsExists("post_id",  $this->data);
        $this->checkFieldIsIntenger("post_id",  $this->data);
        $this->checkFieldIsNumeric("post_id",  $this->data);
        $this->checkFieldIsNaturalNumber("post_id",  $this->data);


        $this->CheckFieldIsRequired("user_id",  $this->data);
        $this->checkFieldIsExists("user_id",  $this->data);
        $this->checkFieldIsIntenger("user_id",  $this->data);
        $this->checkFieldIsNumeric("user_id",  $this->data);
        $this->checkFieldIsNaturalNumber("user_id",  $this->data);

        $this->CheckFieldIsRequired("stock_id",  $this->data);
        $this->checkFieldIsExists("stock_id",  $this->data);
        $this->checkFieldIsIntenger("stock_id",  $this->data);
        $this->checkFieldIsNumeric("stock_id",  $this->data);
        $this->checkFieldIsNaturalNumber("stock_id",  $this->data);
    }
    public function checkData()
    {

        $this->mainApiName = 'api.mobile.posts.store';
        $this->mainModelName = Post::class;
        $this->mainApiMethod = 'post';
        $this->auhUser = $this->SanctumActingAs();
        $this->stockModel = Stock::factory()->create();

        $this->data = [
            "body" => $this->faker->word(),
            "is_main" => true,
            "user_id" =>   $this->auhUser->id,
            "stock_id" =>  $this->stockModel->id,
        ];
    }
}
