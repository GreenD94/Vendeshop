<?php

namespace Tests\Feature\stock;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StockUpdateTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->checkData();
        $this->checkUpdate();
        $this->checkSomeRibbonUpdate();
        $this->checkAllRibbonUpdate();
    }
    public function checkData(): void
    {
        $createdModel = User::factory()->create();
        Sanctum::actingAs(
            $createdModel,
            ['*']
        );
        $this->dataheaders['Authorization'] = 'Bearer ' . $createdModel->createToken($createdModel->id . $createdModel->name . uniqid())->plainTextToken;

        $this->createdModel = Stock::factory()->create();
        $this->assertModelExists($this->createdModel);
        $this->assertDatabaseCount('stocks', 1);
        $this->assertDatabaseCount('colors', 1);
        $this->assertDatabaseCount('images', 2);
        $this->assertDatabaseCount('sizes', 1);
        $this->assertDatabaseCount('ribbons', 1);
        $this->assertDatabaseHas('stocks', $this->createdModel->getOriginal());
        $this->createdModel->name = "test";
        $this->createdModels = Stock::factory()->count(2)->create();
        $this->someRibbon = [
            "id" => [
                $this->createdModels[0]->id,
                $this->createdModels[0]->id
            ],
            "ribbon_id" => 1
        ];
        $this->allRibbon = [
            "id" => "*",
            "ribbon_id" => 1
        ];
    }

    public function checkUpdate(): void
    {
        $response = $this->putJson('/api/mobile/stocks', $this->createdModel->toArray(), $this->dataheaders);
        $response->assertStatus(200);

        $this->assertDatabaseHas('stocks',  [
            'id' => $this->createdModel->id,
            'price' => $this->createdModel->price,
            'mock_price' => $this->createdModel->mock_price,
            'credits'  => $this->createdModel->credits,
            'discount'   => $this->createdModel->discount,
            'cover_image_id' => $this->createdModel->cover_image_id,
            'description' => $this->createdModel->description,
            'name' => $this->createdModel->name,
            'color_id' => $this->createdModel->color_id,
            'size_id' => $this->createdModel->size_id,
            'ribbon_id' => $this->createdModel->ribbon_id
        ]);



        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1->has('price')
                            ->has('mock_price')
                            ->has('credits')
                            ->has('discount')
                            ->has('name')
                            ->has('description')
                            ->has('id')
                            ->has('price')
                            ->has('mock_price')
                            ->has('credits')
                            ->has('discount')

                            ->where('name',  $this->createdModel->name)
                            ->where('description',  $this->createdModel->description)
                            ->where('id',  $this->createdModel->id)->etc()
                    )
            );
    }

    public function checkSomeRibbonUpdate(): void
    {
        $response = $this->putJson('/api/mobile/stocks', $this->someRibbon, $this->dataheaders);
        $response->assertStatus(200);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',

                    )


            );
    }

    public function checkAllRibbonUpdate(): void
    {
        $response = $this->putJson('/api/mobile/stocks', $this->allRibbon, $this->dataheaders);
        $response->assertStatus(200);

        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data'
                    )

            );
    }
}
