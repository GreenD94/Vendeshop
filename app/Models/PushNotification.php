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
                    "fcm_options" => []
                ]

            ],
        ];

        if ($imageUrl) $message['message']['android']['notification']['image'] = $imageUrl;
        if ($imageUrl) $message['message']['apns']['fcm_options']['image'] = $imageUrl;



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



    // static public function sendPushNotification($to, $tittle, $body, $is_admin_key = true, $tittle2 = null, $imageUrl = null)
    // {

    //     $adminKey = 'AAAAfZ5vJB0:APA91bGK55jAKvgBnUegqDRR_jWapgAlTyhfrBHM8arLE75O8bNxF5-Sv_cOdUvNoSUcRB2e6V1WUWqmp5hrhMeCO-bh4li5attOPkgv4CaK0EL86aZTSUBE7Z5qE5Y-LkzJsdt6f_Yr';
    //     $userKey = 'AAAA2TeBH7g:APA91bGVTNgobOu4zRO7z4k5svEtC0J3e2E7ck6UzbeF7v65VZIK668BSlJReUJtQlVd_esqI4uOpcbDha1SVqGk-8qMfRK2khuHjUjeCqiJRZnegL0zMbW714eqGZ5UFsSyXsmNkzqJ';
    //     $url = 'https://fcm.googleapis.com/fcm/send';
    //     $FcmToken = null;
    //     if ($to == "*") {
    //         $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
    //     } else {
    //         $FcmToken = User::whereIn('id', $to)->whereNotNull('device_key')->pluck('device_key')->all();
    //     }
    //     if (empty($FcmToken)) return ["data" => [], "message" => null, "code" => 200];;
    //     if (empty($FcmToken)) return ["data" => $to, "message" => "validation error: the users with these ids do not have a device key or they do not exits in database", "code" => 422];


    //     $serverKey = $is_admin_key ? $adminKey : $userKey;

    //     $data = [
    //         "registration_ids" => $FcmToken,
    //         "data" => $body,
    //         "notification" => [
    //             "title" => $tittle,
    //             "body" =>  $tittle2 ?? "Vende Shop",
    //         ],
    //     ];
    //     $imageUrl = "https://upload.wikimedia.org/wikipedia/commons/4/47/PNG_transparency_demonstration_1.png";
    //     if ($imageUrl) $data["android"] = [
    //         'notification' => [
    //             'image' => $imageUrl
    //         ]
    //     ];
    //     if ($imageUrl) $data["apns"] = [
    //         'payload' => [
    //             "aps" => [
    //                 'mutable-content' => 1
    //             ]
    //         ],
    //         "fcm_options" => [
    //             "image" => $imageUrl
    //         ]
    //     ];
    //     if ($imageUrl) $data["webpush"] = [
    //         'headers' => [
    //             "image" => $imageUrl
    //         ],

    //     ];


    //     $encodedData = json_encode($data);

    //     $headers = [
    //         'Authorization:key=' . $serverKey,
    //         'Content-Type: application/json',
    //     ];

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    //     // Disabling SSL Certificate support temporarly
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    //     // Execute post
    //     $result = curl_exec($ch);

    //     if ($result === FALSE) {
    //         return ["data" => $ch, "message" => "error al conectar con https://fcm.googleapis.com/fcm/send", "code" => 500];
    //     }
    //     // Close connection
    //     curl_close($ch);
    //     return ["data" => $result, "message" => null, "code" => 200];
    // }





    // static public function sendPushNotification($to, $tittle, $body, $is_admin_key = true, $tittle2 = null, $imageUrl = null)
    // {

    //     $adminKey = 'AAAAfZ5vJB0:APA91bGK55jAKvgBnUegqDRR_jWapgAlTyhfrBHM8arLE75O8bNxF5-Sv_cOdUvNoSUcRB2e6V1WUWqmp5hrhMeCO-bh4li5attOPkgv4CaK0EL86aZTSUBE7Z5qE5Y-LkzJsdt6f_Yr';
    //     $userKey = 'AAAA2TeBH7g:APA91bGVTNgobOu4zRO7z4k5svEtC0J3e2E7ck6UzbeF7v65VZIK668BSlJReUJtQlVd_esqI4uOpcbDha1SVqGk-8qMfRK2khuHjUjeCqiJRZnegL0zMbW714eqGZ5UFsSyXsmNkzqJ';
    //     $url = 'https://fcm.googleapis.com/fcm/send';
    //     $FcmToken = null;
    //     $isForASingleUser = false;
    //     $badge = 1;
    //     if ($to == "*") {
    //         $FcmToken = User::whereNotNull('device_key')->pluck('device_key')->all();
    //     } else {
    //         $FcmToken = User::whereIn('id', $to)->whereNotNull('device_key')->pluck('device_key')->all();
    //         $isForASingleUser = count($to) == 1;
    //     }

    //     if (empty($FcmToken)) return ["data" => [], "message" => null, "code" => 200];;
    //     if (empty($FcmToken)) return ["data" => $to, "message" => "validation error: the users with these ids do not have a device key or they do not exits in database", "code" => 422];
    //     if ($isForASingleUser) $badge = User::find($to[0])->unreadNotifications()->count() + 1;

    //     $serverKey = $is_admin_key ? $adminKey : $userKey;

    //     $data = [
    //         "registration_ids" => $FcmToken,
    //         "data" => $body,
    //         "notification" => [
    //             "title" => $tittle,
    //             "body" =>  $tittle2 ?? "Vende Shop",
    //             // "sound" => "alert.aiff"
    //         ],
    //         // "priority" => "high",
    //         // "apns" => [
    //         //     "payload" => [
    //         //         "aps" => [
    //         //             'badge' => $badge
    //         //         ],
    //         //         "messageID" => "ABCDEFGHIJ"
    //         //     ]
    //         // ],

    //     ];




    //     if ($imageUrl) $data["android"] = [
    //         'notification' => [
    //             'imageUrl' => $imageUrl
    //         ]
    //     ];
    //     if ($imageUrl) $data["apns"] = [
    //         'payload' => [
    //             "aps" => [
    //                 'mutable-content' => 1
    //             ]
    //         ],
    //         "fcm_options" => [
    //             "image" => $imageUrl
    //         ]
    //     ];
    //     if ($imageUrl) $data["webpush"] = [
    //         'headers' => [
    //             "image" => $imageUrl
    //         ],

    //     ];



    //     //   if ($imageUrl) $data["notification"]["imageUrl"] = $imageUrl;
    //     $encodedData = json_encode($data);

    //     $headers = [
    //         'Authorization:key=' . $serverKey,
    //         'Content-Type: application/json',
    //     ];

    //     $ch = curl_init();

    //     curl_setopt($ch, CURLOPT_URL, $url);
    //     curl_setopt($ch, CURLOPT_POST, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    //     curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    //     // Disabling SSL Certificate support temporarly
    //     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
    //     // Execute post
    //     $result = curl_exec($ch);

    //     if ($result === FALSE) {
    //         return ["data" => $ch, "message" => "error al conectar con https://fcm.googleapis.com/fcm/send", "code" => 500];
    //     }
    //     // Close connection
    //     curl_close($ch);
    //     return ["data" => $result, "message" => null, "code" => 200];
    // }


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
                //  if ($result1["code"] != 200 || $result2["code"] != 200) dd($result1, $result2);
            }



            //    return ['data' => $result["data"], "message" => $result["message"], "code" =>  $result["code"]];
        }

        $modelData['body'] = json_encode($modelData['body']);
        $usersId = collect([]);
        if ($modelData['user_id'] == "*") $usersId = User::whereNotNull('device_key')->pluck('id')->all();
        if (is_array($modelData['user_id'])) $usersId = User::whereIn('id', $modelData['user_id'])->whereNotNull('device_key')->pluck('id')->all();
        $data = [];
        foreach ($usersId as $key => $id) {
            $modelData['user_id'] = $id;
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
