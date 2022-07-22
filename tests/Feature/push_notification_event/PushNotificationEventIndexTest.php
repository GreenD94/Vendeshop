<?php

namespace Tests\Feature\push_notification_event;

use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationEventIndexTest extends TestCase
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
    }
    public function checkData(): void
    {
        $this->mainApiName = 'api.mobile.push_notification_events.index';
        $this->mainModelName = PushNotificationEvent::class;
        $this->mainApiMethod = 'get';
        $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["push_notification_events" => 1]);
        $this->createdModels = $this->mainModelCreateMany([], ["push_notification_events" => 20], 19);
        $this->data = $this->createdModels[18]->toArray();
    }
    public function checkindex(): void
    {
        $params = array();
        $response = $this->CallIndexApi(null, $params);


        $this->data["data_length"] = 20;
        $this->ResponseAssertJson($response, $this->data);
    }
}
