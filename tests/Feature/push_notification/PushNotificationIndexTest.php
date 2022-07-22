<?php

namespace Tests\Feature\push_notification;

use App\Models\PushNotification;
use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationIndexTest extends TestCase
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
        $this->checkindex();
        $this->checkUserId();
        //   $this->checkAuth();
    }
    public function checkData(): void
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

        $this->createdModel = $this->mainModelCreate($this->data, ["push_notifications" => 1]);
        $this->createdModels = $this->mainModelCreateMany([], ["push_notifications" => 20], 19);
        $this->data = $this->createdModels[18]->toArray();
        $this->data["event_id"] = $this->createdModels[18]->event->id;
        $this->data["event_name"] = $this->createdModels[18]->event->name;
    }
    public function checkindex(): void
    {
        $params = array("page" => 1);
        $response = $this->CallIndexApi(null, $params);


        $this->data["data_length"] = 5;

        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkUserId(): void
    {
        $params = array("page" => 1, "user_id" =>  $this->createdModels[18]->id);
        $response = $this->CallIndexApi(null, $params);

        $this->data["data_length"] = 1;


        $this->ResponseAssertJson($response, $this->data);
    }
    public function checkAuth(): void
    {
        $params = array("page" => 1);
        $response = $this->CallIndexApi(null, []);

        $this->data["data_length"] = 1;
        $this->ResponseAssertJson($response, $this->data);
    }
}
