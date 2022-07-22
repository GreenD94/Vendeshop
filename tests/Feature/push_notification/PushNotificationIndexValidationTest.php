<?php

namespace Tests\Feature\push_notification;

use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationIndexValidationTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;
    use CheckHelpers;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->checkData();
        $params = array("page" => 1);


        $this->checkFieldIsIntenger("page", $params);
        $this->checkFieldIsNumeric("page", $params);
        $this->checkFieldIsNaturalNumber("page", $params);

        $params = array("user_id" => 11, "page" => 1);

        $this->checkFieldIsExists("user_id", $params);
        $this->checkFieldIsIntenger("user_id", $params);
        $this->checkFieldIsNumeric("user_id", $params);
        $this->checkFieldIsNaturalNumber("user_id", $params);
    }
    public function checkData()
    {
        $this->mainApiName = 'api.mobile.push_notifications.index';
        $this->mainModelName = PushNotification::class;
        $this->mainApiMethod = 'get';
        $this->authUser = $this->SanctumActingAs();
        $this->pushNotificationEventData = ['name' => $this->faker->name()];
        $this->pushNotificationEventModel = PushNotificationEvent::factory()->create($this->pushNotificationEventData);
        $this->data = [
            'user_id' =>  $this->authUser->id,
            'tittle' => $this->faker->title(),
            'body' => $this->faker->title(),
            'fcm_token' => $this->faker->title(),
            'is_new' => true,
            'push_notification_event_id' => $this->pushNotificationEventModel->id,
        ];
    }
}
