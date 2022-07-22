<?php

namespace Database\Factories;

use App\Models\address;
use App\Models\Stock;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $user->addresses()->attach(address::factory()->create(['is_favorite' => true])->id);
        return [
            "body" => $this->faker->sentence(),
            "is_main" => false,
            "user_id" => $user->id,
            "stock_id" => Stock::factory(),
        ];
    }
}
