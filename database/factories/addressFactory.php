<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class addressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'address' => $this->faker->address(),
            'city_id' => 1,
            'city_name' => $this->faker->address(),
            'street' => $this->faker->streetAddress(),
            'postal_code' => $this->faker->postcode(),
            'deparment' => $this->faker->name(),
            'phone_number' => $this->faker->phoneNumber(),
            'is_favorite' => false
        ];
    }
}
