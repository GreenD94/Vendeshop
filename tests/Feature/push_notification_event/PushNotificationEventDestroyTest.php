<?php

namespace Tests\Feature\push_notification_event;

use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;



class PushNotificationEventDestroyTest extends TestCase
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
        $this->checkDestroy();
    }


    public function checkData()
    {
        $this->mainApiName = 'api.mobile.push_notification_events.destroy';
        $this->mainModelName = PushNotificationEvent::class;
        $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["push_notification_events" => 1]);
    }

    public function checkDestroy()
    {
        $params = array("id" => $this->createdModel->id);
        $response =  $this->CallDestroyApi(null, $params, ["push_notification_events" => 0]);

        $this->data["id"] = $this->createdModel->id;
        $this->ResponseAssertJson($response, $this->data);
    }
}
