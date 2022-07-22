<?php

namespace Tests\Feature\push_notification;

use App\Models\PushNotification;
use App\Models\PushNotificationEvent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationUpdateTest extends TestCase
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
        $this->checkUpdateUserId();
        $this->checkUpdateTittle();
        $this->checkUpdateBody();
        $this->checkUpdateFCMToken();
        $this->checkUpdateIsNew();
        $this->checkUpdatePushNotificationEventId();
    }
    public function checkData(): void
    {
        $this->mainApiName = 'api.mobile.push_notifications.update';
        $this->mainModelName = PushNotification::class;
        $this->mainApiMethod = 'put';
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
        $this->data['id'] = $this->createdModel->id;
        $this->data["event_id"] = $this->pushNotificationEventModel->id;
        $this->data["event_name"] = $this->pushNotificationEventData["name"];
    }
    public function checkUpdateUserId()
    {
        $this->data["user_id"] = User::factory()->create()->id;
        $params = array('id' => $this->createdModel->id, "user_id" => $this->data["user_id"]);
        $response = $this->CallUpdateApi(null,  $params, ["push_notifications" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateTittle()
    {
        $this->data["tittle"] = $this->faker->title();
        $params = array('id' => $this->createdModel->id, "tittle" => $this->data["tittle"]);
        $response = $this->CallUpdateApi(null,  $params, ["push_notifications" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }

    public function checkUpdateBody()
    {
        $this->data["body"] = $this->faker->title();
        $params = array('id' => $this->createdModel->id, "body" => $this->data["body"]);
        $response = $this->CallUpdateApi(null,  $params, ["push_notifications" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateFCMToken()
    {
        $this->data["fcm_token"] = $this->faker->title();
        $params = array('id' => $this->createdModel->id, "fcm_token" => $this->data["fcm_token"]);
        $response = $this->CallUpdateApi(null,  $params, ["push_notifications" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdateIsNew()
    {
        $this->data["is_new"] = false;
        $params = array('id' => $this->createdModel->id, "is_new" => $this->data["is_new"]);
        $response = $this->CallUpdateApi(null,  $params, ["push_notifications" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUpdatePushNotificationEventId()
    {
        $PushNotificationEventModel = PushNotificationEvent::factory()->create(["name" => "aaaaaa"]);
        $this->data["push_notification_event_id"] = $PushNotificationEventModel->id;
        $this->data["event_id"] = $PushNotificationEventModel->id;
        $this->data["event_name"] = "aaaaaa";
        $params = array('id' => $this->createdModel->id, "push_notification_event_id" => $this->data["push_notification_event_id"]);
        $response = $this->CallUpdateApi(null,  $params, ["push_notifications" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
