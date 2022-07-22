<?php

namespace Tests\Feature\push_notification;

use App\Models\Order;
use App\Models\PushNotification;
use App\Models\PushNotificationEvent;
use Database\Seeders\FakeDataSeeder;
use Database\Seeders\MasterUserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\CheckHelpers;
use Tests\TestCase;

class PushNotificationStoreTest extends TestCase
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
        $this->checkStore();
    }
    public function checkData(): void
    {
        $this->seed(MasterUserSeeder::class);
        $this->seed(FakeDataSeeder::class);
        $this->mainApiName = 'api.mobile.push_notifications.store';
        $this->mainModelName = PushNotification::class;
        $this->mainApiMethod = 'post';
        $this->authUser = $this->SanctumActingAs();
        $this->authUser->device_key = "eyJhbGciOiJSUzI1NiIsImtpZCI6Ijg2MTY0OWU0NTAzMTUzODNmNmI5ZDUxMGI3Y2Q0ZTkyMjZjM2NkODgiLCJ0eXAiOiJKV1QifQ.eyJpc3MiOiJodHRwczovL2FjY291bnRzLmdvb2dsZS5jb20iLCJhenAiOiI5MzI5MzkxMTIzNzYtcWNocWJhdHEzYjhzdWducWRlcWlvYThuMnN2dmRsYnMuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJhdWQiOiI5MzI5MzkxMTIzNzYtcWNocWJhdHEzYjhzdWducWRlcWlvYThuMnN2dmRsYnMuYXBwcy5nb29nbGV1c2VyY29udGVudC5jb20iLCJzdWIiOiIxMDg2Nzg1NjU5NzM4MTgxNjU5MzEiLCJlbWFpbCI6ImN1ZXZhc3JhZmFlbGNAZ21haWwuY29tIiwiZW1haWxfdmVyaWZpZWQiOnRydWUsImF0X2hhc2giOiJxS29WalZFcW16bXhoLVJ4U2Vyb0tRIiwibm9uY2UiOiJnQ191ckcybUV5dTVZZjdrb2hZbXRzZ2MxWmY3N1dUWl9RWWJhWk5maWJnIiwibmFtZSI6IlJhZmFlbCBDdWV2YXMiLCJwaWN0dXJlIjoiaHR0cHM6Ly9saDMuZ29vZ2xldXNlcmNvbnRlbnQuY29tL2EtL0FPaDE0R2dRTmRsd3VZLW1XeTk0Q0l3eG13bFlHT2J6QzBnbmN1ajZrVEE4dUE9czk2LWMiLCJnaXZlbl9uYW1lIjoiUmFmYWVsIiwiZmFtaWx5X25hbWUiOiJDdWV2YXMiLCJsb2NhbGUiOiJlcyIsImlhdCI6MTY1MTI2MjY3OSwiZXhwIjoxNjUxMjY2Mjc5fQ.CgRBnQXFQGvX4tlSUJ2o-F2_V8_cGRL1BssnyoCdGnPCQqWzL5Mu0p8XrwSn34LS1nv7StzRMKzw4NL6_2fAzFtRadOUegHkAhnrMcbxslJQ3ZIyFExZUiHE2gHX74c2U8D8dQCvNB4bGqJ_O4MVY700xRqFZmbJ0Eyo5WWcxR6WhggB5Ilbz20OyENWVqywk6uUS-9pVa5-58MwmM1d55GEMez5P7zbuOWAJtsljng0TCg9tseUIegQ2xd_yOe6D_T2NWL6HXfpAJT2StjcePV7UpEPnuJLN4Y6gnRigq984ZnRMPHRo-XlIdcvivvHWnDaL7Nb0et51KVOXQtMSg";
        $this->authUser->save();
        $this->pushNotificationEventData = ['name' => $this->faker->name()];
        $this->pushNotificationEventModel = PushNotificationEvent::factory()->create($this->pushNotificationEventData);
        Order::factory()->create();
        $this->data = [
            'user_id' =>  '*',
            'tittle' => $this->faker->title(),
            'body' => [
                "order_id" => 1
            ],
            'fcm_token' => $this->faker->title(),
            'is_live' => false,
            'push_notification_event_id' => PushNotificationEvent::find(3)->id,
        ];
    }
    public function checkStore(): void
    {
        $response = $this->CallStoreApi(null,  $this->data, ["push_notifications" => 1]);
        $this->data["event_id"] = $this->pushNotificationEventModel->id;
        $this->data["event_name"] = $this->pushNotificationEventData["name"];
        $this->ResponseAssertJson($response, $this->data);
    }
}
