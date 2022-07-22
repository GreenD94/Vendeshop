<?php

namespace Tests\Feature\post;

use App\Models\Stock;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PostStoreTest extends TestCase
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
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["posts" => 1, "users" => 1, "stocks" => 1]);


        $this->data["user_name"] =  $this->auhUser->first_name . ' ' . $this->auhUser->last_name;
        $this->ResponseAssertJson($response, $this->data);
    }
}
