<?php

namespace Tests\Feature\push_notification_event;

use App\Models\PushNotificationEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationEventUpdateValidationTest extends TestCase
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
        $this->data['id'] = $this->createdModel->id;
        $this->CheckFieldIsUnique("name",  $this->data);
        $this->data['name'] = 'aaaaa';
        $this->CheckFieldIsRequired("id", $this->data);
        $this->checkFieldIsExists("id", $this->data);
        $this->checkFieldIsIntenger("id", $this->data);
        $this->checkFieldIsNumeric("id", $this->data);
        $this->checkFieldIsNaturalNumber("id", $this->data);
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
}
