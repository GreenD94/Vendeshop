<?php

namespace Tests\Feature\push_notification_event;

use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationEventStoreValidationTest extends TestCase
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

        $this->CheckFieldIsRequired("name",  $this->data);
        $this->CheckFieldIsUnique("name",  $this->data);
    }
    public function checkData()
    {
        $this->mainApiName = 'api.mobile.push_notification_events.store';
        $this->mainModelName = PushNotificationEvent::class;
        $this->mainApiMethod = 'post';
        $this->SanctumActingAs();
        $this->data = [
            'name' => $this->faker->name
        ];
        $this->createdModel = $this->mainModelCreate($this->data, ["push_notification_events" => 1]);
    }
}
