<?php

namespace Database\Factories;

use App\Models\Image;
use Illuminate\Database\Eloquent\Factories\Factory;

class BackgroundFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            "is_favorite" => false,
            "color" => $this->faker->hexColor(),
            "image_id" => Image::factory(),
        ];
    }
}
