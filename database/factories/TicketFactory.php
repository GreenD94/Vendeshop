<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [

            'user_id' => User::factory(),
            'value' => $this->faker->randomNumber(),
            'expiration_time' => Carbon::now(),
            'is_used' => false,
            'is_active' => true,
        ];
    }
}
