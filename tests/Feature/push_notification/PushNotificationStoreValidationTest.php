<?php

namespace Tests\Feature\push_notification;

use App\Models\PushNotification;
use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationStoreValidationTest extends TestCase
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


        $this->CheckFieldIsRequired("user_id",  $this->data);
        $this->checkFieldIsIntenger("user_id", $this->data);
        $this->checkFieldIsNumeric("user_id", $this->data);
        $this->checkFieldIsNaturalNumber("user_id", $this->data);


        $this->CheckFieldIsRequired("push_notification_event_id",  $this->data);
        $this->checkFieldIsIntenger("push_notification_event_id", $this->data);
        $this->checkFieldIsNumeric("push_notification_event_id", $this->data);
        $this->checkFieldIsNaturalNumber("push_notification_event_id", $this->data);


        $this->CheckFieldIsRequired("tittle",  $this->data);
        $this->CheckFieldIsRequired("body",  $this->data);
        $this->CheckFieldIsRequired("is_new",  $this->data);
        $this->CheckFieldIsBoolean("is_new",  $this->data);
    }
    public function checkData()
    {
        $this->mainApiName = 'api.mobile.push_notifications.store';
        $this->mainModelName = PushNotification::class;
        $this->mainApiMethod = 'post';
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
