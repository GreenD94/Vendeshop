<?php

namespace Database\Factories;

use App\Models\PushNotificationEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PushNotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' =>  User::factory(),
            'tittle' => $this->faker->title(),
            'body' => $this->faker->title(),
            'fcm_token' => $this->faker->title(),
            'is_new' => true,
            'push_notification_event_id' => PushNotificationEvent::factory(),
        ];
    }
}
