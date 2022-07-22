<?php

namespace Database\Factories;

use App\Models\address;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
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
            'user_first_name' => $this->faker->name(),
            'user_last_name' => $this->faker->name(),
            'user_phone' => $this->faker->phoneNumber(),
            'user_email' => $this->faker->email(),
            'user_birth_date' => Carbon::now(),
            'user_email_verified_at' => Carbon::now(),
            'user_avatar_id' => 1,
            'user_avatar_url' => $this->faker->imageUrl(),

            'address_id' => 1,
            'address_address' => $this->faker->address(),
            'address_city_id' => 1,
            'address_city_name' => $this->faker->city(),
            'address_street' => $this->faker->streetAddress(),
            'address_postal_code' => $this->faker->randomDigit(),
            'address_deparment' => $this->faker->name(),
            'address_phone_number' => $this->faker->phoneNumber(),
            'billing_address_id' => 2,
            'billing_address_address' => $this->faker->address(),
            'billing_address_city_id' => 1,
            'billing_address_city_name' => $this->faker->city(),
            'billing_address_street' => $this->faker->streetAddress(),
            'billing_address_postal_code' => $this->faker->randomDigit(),
            'billing_address_deparment' => $this->faker->name(),
            'billing_address_phone_number' => $this->faker->phoneNumber(),
            'payment_type_id' => 1,
            'payment_type_name' => "en espera",
            'total' => $this->faker->randomDigit(),

        ];
    }
}
