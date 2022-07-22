<?php

namespace Tests\Feature\push_notification_event;

use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationEventUpdateTest extends TestCase
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
        $this->checkUpdate();
    }
    public function checkData(): void
    {
        $this->mainApiName = 'api.mobile.push_notification_events.update';
        $this->mainModelName = PushNotificationEvent::class;
        $this->mainApiMethod = 'put';
        $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["push_notification_events" => 1]);
    }
    public function checkUpdate()
    {
        $this->data['name'] = 'sssss';
        $this->data['id'] = $this->createdModel->id;
        $response = $this->CallUpdateApi(null,  $this->data, ["push_notification_events" => 1]);
        $this->ResponseAssertJson($response, $this->data);
    }
}
