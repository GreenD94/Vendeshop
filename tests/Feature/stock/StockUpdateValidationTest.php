<?php

namespace Tests\Feature\stock;

use App\Models\Stock;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StockUpdateValidationTest extends TestCase
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
        $this->checkIdRequired();
        $this->checkIdExists();
        $this->checkIdIsIntenger();
        $this->checkIdIsNumeric();
        $this->checkIdIsNaturalNumber();

        $this->checkPriceNumeric();
        $this->checkMockPriceNumeric();
        $this->checkCreditsNumeric();
        $this->checkDiscountNumeric();



        $this->checkCoverImageIdExists();
        $this->checkCoverImageIdIsIntenger();
        $this->checkCoverImageIdIsNumeric();
        $this->checkCoverImageIdIsNaturalNumber();



        $this->checkColorIdExists();
        $this->checkColorIdIsIntenger();
        $this->checkColorIdIsNumeric();
        $this->checkColorIdIsNaturalNumber();


        $this->checkSizeIdExists();
        $this->checkSizeIdIsIntenger();
        $this->checkSizeIdIsNumeric();
        $this->checkSizeIdIsNaturalNumber();



        $this->checkRibbonIdExists();
        $this->checkRibbonIdIsIntenger();
        $this->checkRibbonIdIsNumeric();
        $this->checkRibbonIdIsNaturalNumber();


        $this->checkIdsRequired();
        $this->checkIdsExists();
        $this->checkIdsIsIntenger();
        $this->checkIdsIsNumeric();
        $this->checkIdsIsNaturalNumber();
    }
    public function checkData(): void
    {
        $AuthdUser = User::factory()->create();
        $this->dataheaders = [];
        $this->dataheaders['Authorization'] = 'Bearer ' . $AuthdUser->createToken($AuthdUser->id . $AuthdUser->name . uniqid())->plainTextToken;

        Sanctum::actingAs(
            $AuthdUser,
            ['*']
        );
        Stock::factory()->create();
    }

    public function checkIdRequired(): void
    {
        $body = array();
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );
    }

    public function checkCoverImageIdExists()
    {
        $body = array("id" => 1, "cover_image_id" => 22);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('cover_image_id')
                    )
            );
    }
    public function checkCoverImageIdIsIntenger()
    {
        $body = array("id" => 1, "cover_image_id" => 1.1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('cover_image_id')
                    )
            );
    }
    public function checkCoverImageIdIsNumeric()
    {
        $body = array("id" => 1, "cover_image_id" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('cover_image_id')
                    )
            );
    }
    public function checkCoverImageIdIsNaturalNumber()
    {
        $body = array("id" => 1, "cover_image_id" => -1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('cover_image_id')
                    )
            );
    }



    public function checkIdExists()
    {
        $body = array("id" => 22);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );
    }
    public function checkIdIsIntenger()
    {
        $body = array("id" => 1.1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );
    }
    public function checkIdIsNumeric()
    {
        $body = array("id" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );
    }
    public function checkIdIsNaturalNumber()
    {
        $body = array("id" => -1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );
    }








    public function checkSizeIdExists()
    {
        $body = array("id" => 1, "size_id" => 22);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('size_id')
                    )
            );
    }
    public function checkSizeIdIsIntenger()
    {
        $body = array("id" => 1, "size_id" => 1.1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('size_id')
                    )
            );
    }
    public function checkSizeIdIsNumeric()
    {
        $body = array("id" => 1, "size_id" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('size_id')
                    )
            );
    }
    public function checkSizeIdIsNaturalNumber()
    {
        $body = array("id" => 1, "size_id" => -1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('size_id')
                    )
            );
    }



    public function checkRibbonIdExists()
    {
        $body = array("id" => 1, "ribbon_id" => 22);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('ribbon_id')
                    )
            );
    }
    public function checkRibbonIdIsIntenger()
    {
        $body = array("id" => 1, "ribbon_id" => 1.1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('ribbon_id')
                    )
            );
    }
    public function checkRibbonIdIsNumeric()
    {
        $body = array("id" => 1, "ribbon_id" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('ribbon_id')
                    )
            );
    }
    public function checkRibbonIdIsNaturalNumber()
    {
        $body = array("id" => 1, "ribbon_id" => -1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('ribbon_id')
                    )
            );
    }



    public function checkColorIdExists()
    {
        $body = array("id" => 1, "color_id" => 22);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('color_id')
                    )
            );
    }
    public function checkColorIdIsIntenger()
    {
        $body = array("id" => 1, "color_id" => 1.1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('color_id')
                    )
            );
    }
    public function checkColorIdIsNumeric()
    {
        $body = array("id" => 1, "color_id" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('color_id')
                    )
            );
    }
    public function checkColorIdIsNaturalNumber()
    {
        $body = array("id" => 1, "color_id" => -1);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('color_id')
                    )
            );
    }



    public function checkIdsRequired(): void
    {
        $body = array("id" => []);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('id')
                    )
            );
    }

    public function checkIdsExists()
    {
        $body = array("id" => [22]);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);

        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data'
                    )

            );
    }
    public function checkIdsIsIntenger()
    {
        $body = array("id" => [1.1]);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data'
                    )
            );
    }
    public function checkIdsIsNumeric()
    {
        $body = array("id" => ["aaa"]);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data'
                    )
            );
    }
    public function checkIdsIsNaturalNumber()
    {
        $body = array("id" => [-1]);
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data'
                    )
            );
    }

    public function checkPriceNumeric()
    {
        $body = array("id" => 1, "price" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('price')
                    )
            );
    }

    public function checkMockPriceNumeric()
    {
        $body = array("id" => 1, "mock_price" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('mock_price')
                    )
            );
    }

    public function checkCreditsNumeric()
    {
        $body = array("id" => 1, "credits" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('credits')
                    )
            );
    }


    public function checkDiscountNumeric()
    {
        $body = array("id" => 1, "discount" => "aaa");
        $response = $this->putJson(route('api.mobile.stocks.update'), $body, $this->dataheaders);
        $response->assertUnprocessable();
        $response
            ->assertJson(
                fn (AssertableJson $json) =>
                $json->has('message')
                    ->has(
                        'data',
                        fn ($json1) =>
                        $json1
                            ->has('discount')
                    )
            );
    }
}
