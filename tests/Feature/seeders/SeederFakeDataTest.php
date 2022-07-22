<?php

namespace Tests\Feature\seeders;

use Database\Seeders\FakeDataSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeederFakeDataTest extends TestCase
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

        $this->seed(FakeDataSeeder::class);




        $this->assertDatabaseCount('stocks',  23);
        $this->assertDatabaseCount('images',  104);
        $this->assertDatabaseCount('videos',  8);
        $this->assertDatabaseCount('categories', 4);
        $this->assertDatabaseCount('colors',  22);
        $this->assertDatabaseCount('sizes', 4);
        $this->assertDatabaseCount('products',  16);
        $this->assertDatabaseCount('banners',  12);
    }
}
