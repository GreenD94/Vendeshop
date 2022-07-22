<?php

namespace Tests\Feature\push_notification;

use App\Models\PushNotification;
use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationDestroyValidationTest extends TestCase
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
        $params = array("id" => $this->createdModel->id);
        $this->CheckFieldIsRequired("id", $params);
        $this->checkFieldIsExists("id", $params);
        $this->checkFieldIsIntenger("id", $params);
        $this->checkFieldIsNumeric("id", $params);
        $this->checkFieldIsNaturalNumber("id", $params);
    }
    public function checkData()
    {
        $this->mainApiName = 'api.mobile.push_notifications.destroy';
        $this->mainModelName = PushNotification::class;
        $this->mainApiMethod = 'delete';
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
        $this->createdModel = $this->mainModelCreate($this->data, ["push_notifications" => 1]);
    }
}
