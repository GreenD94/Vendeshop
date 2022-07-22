<?php

namespace Database\Factories;

use App\Models\Color;
use App\Models\Image;
use App\Models\Ribbon;
use App\Models\Size;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Stock;

class StockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Stock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $mock_price=$this->faker->numberBetween(1, 100);
        $price= $this->faker->numberBetween(1, $mock_price);
        $discount= ($this->faker->numberBetween(0, 99))/100;
        
        return [
            'price' =>$price,
            'mock_price' => $mock_price,
            'credits'  => $this->faker->randomFloat(2),
            'discount'   =>   $discount,
            'cover_image_id' => Image::factory(),
            'description' => $this->faker->sentence(),
            'name' => $this->faker->name(),
            'color_id' => Color::factory(),
            'size_id' => Size::factory(),
            'ribbon_id' => Ribbon::factory(),
        ];
    }
}
