<?php

namespace App\Models;

use App\Http\Resources\ComercialResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\PushNotificationEventResource;
use App\Http\Resources\PushNotificationResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PushNotification extends Model
{
    use HasFactory;

    protected $with = ['event'];
    protected $fillable = [
        'user_id',
        'tittle',
        'body',
        'is_check',
        'is_new',
        'push_notification_event_id',
    ];
    protected $table = 'push_notifications';
    static public  $ADMIN_PROJECT_ID = "vende-shop-admin";
    static public  $USER_PROJECT_ID = "vende-shop-217fd";
    public function event()
    {
        return $this->belongsTo(PushNotificationEvent::class, 'push_notification_event_id', 'id');
    }



    public function scopeWhenUserId($query, $user_id)
    {
        if ($user_id) $query->where("user_id", $user_id);
    }


    static public function sendPushNotification($to, $tittle, $body, $projectID, $googleAccesToken, $tittle2 = null, $imageUrl = null, $badge = null)
    {

        $url = "https://fcm.googleapis.com/v1/projects/" . $projectID . "/messages:send";
        $fcmToken = $to;

        $headers = [
            'Authorization: Bearer ' . $googleAccesToken,
            'Content-Type: application/json'
        ];

        $notification_tray = [
            'title'             => $tittle,
            'body'              =>  $tittle2 ?? "Vende Shop",

        ];



        $message = [

            'message' => [
                "token" => $fcmToken,
                'notification'     => $notification_tray,
                'data'             =>   $body,

                "android" => [
                    'notification' => [
                        'image' => $imageUrl,
                        'notification_count' => $badge
                    ]
                ],
                "apns" => [
                    'payload' => [
                        "aps" => [
                            'mutable-content' => 1,
                            'badge' => $badge
                        ]
                    ],
                    'fcm_options' => [
                        'image' => $imageUrl
                    ]

                ]

            ],
        ];

        // if ($imageUrl) $message['message']['android']['notification']['image'] = $imageUrl;
        // if ($imageUrl) $message['message']['apns']['fcm_options'] = [];
        // if ($imageUrl) $message['message']['apns']['fcm_options']['image'] = $imageUrl;



        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($message));

        $result = curl_exec($ch);

        if ($result === FALSE) {

            return ["data" => $ch, "message" =>  $url, "code" => 500, "header" => $headers, "message" => $message, "url" => $url];
        }

        curl_close($ch);

        return ["data" => $result, "message" => null, "code" => 200, "header" => $headers, "message" => $message, "url" => $url];
    }





    static public function sendNewsNotification($modelData, $push_notification_event)
    {

        if ($push_notification_event->id != PushNotificationEvent::$NEWS) return  false;

        $has_comercial_id = array_key_exists('comercial_id', $modelData['body']);
        if (!$has_comercial_id) return ['data' => $modelData['body'], "message" => "comercial_id does not exist on body", "code" => 400];
        $comercial_id = $modelData['body']['comercial_id'];
        $comercial = Comercial::find($comercial_id);
        if (!$comercial) return ['data' => $modelData['body'],  "message" => "comercial_id does not exist on database", "code" =>  400];
        unset($modelData['body']['comercial_id']);
        $modelData['body']['comercial'] =  (new ComercialResource($comercial))->toResponse(app('request'))->getContent();
        $modelData['body']['event'] = (new PushNotificationEventResource($push_notification_event))->toResponse(app('request'))->getContent();

        $fcmUsers = User::getFCMUsers($modelData['user_id'])->get();
        $googleAccessAdminToken = PushNotification::getGoogleAccessAdminToken();
        $googleAccessUserToken = PushNotification::getGoogleAccessUserToken();
        if ($modelData['is_live']) {
            $errorResult = collect([]);
            foreach ($fcmUsers as $key => $user) {
                $modelData['user_id'] = $user->id;
                $isAdminProject = $user->isAdmin() || $user->isMaster();
                $unreadNotification = $user->unreadNotifications()->count() + 1;
                $result = PushNotification::sendPushNotification(
                    $user->device_key,
                    $comercial->tittle ?? "NOTICIAS",
                    $modelData['body'],
                    PushNotification::$USER_PROJECT_ID,
                    $googleAccessUserToken,
                    $comercial->name,
                    Storage::disk('s3')->url(
                        $comercial?->image?->url
                    ),
                    $unreadNotification
                );

                $errorResult->push($result);


                if ($isAdminProject)     $result =  PushNotification::sendPushNotification(
                    $user->device_key,
                    $comercial->tittle ?? "NOTICIAS",
                    $modelData['body'],
                    PushNotification::$ADMIN_PROJECT_ID,
                    $googleAccessAdminToken,
                    $comercial->name,
                    Storage::disk('s3')->url(
                        $comercial?->image?->url
                    ),
                    $unreadNotification

                );
                if ($isAdminProject) $errorResult->push($result);
            }
        }

        $modelData['body']['comercial'] =  new ComercialResource($comercial);
        $modelData['body']['event'] = new PushNotificationEventResource($push_notification_event);
        $modelData['body'] = json_encode($modelData['body']);
        $data = [];
        foreach ($fcmUsers as $key => $user) {
            $modelData['user_id'] = $user->id;
            array_push($data, $modelData);
        }
        foreach ($data as $key => $d) {
            PushNotification::create($d);
        }
        return  ['data' => $errorResult, "message" => "success", "code" => 200];
    }


    static public function getGoogleAccessAdminToken()
    {

        $credentialsFilePath =  base_path('google_credential/admin_private_key.json');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $token = $client->getAccessToken();
        return $token['access_token'];
    }

    static public function getGoogleAccessUserToken()
    {

        $credentialsFilePath = base_path('google_credential/user_private_key.json');
        $client = new \Google_Client();
        $client->setAuthConfig($credentialsFilePath);
        $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
        $client->fetchAccessTokenWithAssertion();
        $token = $client->getAccessToken();
        return $token['access_token'];
    }
}
