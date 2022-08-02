<?php

namespace App\Models;

use App\Http\Resources\PostResource;
use App\Http\Resources\PushNotificationEventResource;
use App\Http\Resources\PushNotificationResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;
    protected $with = ['user', 'replies'];
    protected $fillable = ['body', 'is_main', 'user_id', 'stock_id'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function replies()
    {
        return $this->belongsToMany(Post::class, 'replies', 'post_id', 'reply_id');
    }

    public function mainPost()
    {
        return $this->belongsToMany(Post::class, 'replies', 'reply_id', 'post_id');
    }


    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }

    public function scopeWhenisMain($query, $is_main)
    {
        if ($is_main) $query->where('is_main', $is_main);
    }


    public function scopeWhenStockId($query, $stock_id)
    {
        if ($stock_id) $query->where('stock_id',  $stock_id);
    }

    public function scopeWhenId($query, $id)
    {
        if ($id) $query->where('id',  $id);
    }


    public function scopeWhenUserId($query, $user_id)
    {
        if ($user_id) $query->where('user_id',  $user_id);
    }


    public function sendNewReplyNotification($is_alive = true, $mainPost = null, $vv)
    {

        $fcmUsers = User::role('admin')->whereNotNull('device_key')->get();
        $maserId = User::role('master')->whereNotNull('device_key')->get();
        $fcmUsers->concat($maserId);
        $googleAccessAdminToken = PushNotification::getGoogleAccessAdminToken();
        $googleAccessUserToken = PushNotification::getGoogleAccessUserToken();
        if ($vv) $usersId = collect([]);
        if ($mainPost) {
            $replies = $mainPost->replies;
            $replies->push($mainPost);
            $replies_users_id = collect($replies->pluck('user_id')->unique()->values()->all());
            $replies_users_id->push($mainPost->user_id);
            $replies_users_id = $replies_users_id->unique()->values()->all();
            $fcmUsers->concat(User::getFCMUsers($replies_users_id));
        }
        $push_notification_event = PushNotificationEvent::find(PushNotificationEvent::$NEW_REPLY);

        $modelData = [
            "user_id" =>  $fcmUsers[0]->id,
            "tittle" => "new Reply",
            "body" =>  [
                "post" => (new PostResource($this))->toResponse(app('request'))->getContent(),
                'main_post_id' => $mainPost?->id,
                "event" => (new PushNotificationEventResource($push_notification_event))->toResponse(app('request'))->getContent()
            ],
            "is_live" => $is_alive,
            "push_notification_event_id" => $push_notification_event->id,
        ];

        $errorResult = collect([]);
        $from = '';
        if ($this?->user?->first_name)  $from = $this?->user?->first_name . ': ';
        $tittle2 = $from . $this->body;
        if ($is_alive) {

            foreach ($fcmUsers as $key => $user) {


                $modelData["user_id"] = $user->id;
                $isAdminProject = $user->isAdmin() || $user->isMaster();
                $unreadNotification = $user->unreadNotifications()->count() + 1;

                $result = PushNotification::sendPushNotification(
                    $user->device_key,
                    "Nuevo Mensaje",
                    $modelData['body'],
                    PushNotification::$USER_PROJECT_ID,
                    $googleAccessUserToken,
                    $tittle2,
                    null,
                    $unreadNotification
                );

                $errorResult->push($result);


                if ($isAdminProject) $result =  PushNotification::sendPushNotification(
                    $user->device_key,
                    "Nuevo Mensaje",
                    $modelData['body'],
                    PushNotification::$ADMIN_PROJECT_ID,
                    $googleAccessAdminToken,
                    $tittle2,
                    null,
                    $unreadNotification

                );
                if ($isAdminProject) $errorResult->push($result);
            }
            if ($result["code"] != 200)  return ['data' => $result["data"], "message" => $result["message"], "code" =>  $result["code"]];
        }
        $modelData['body']['post'] =  new PostResource($this);
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
        return  ['data' => [], "message" => "success", "code" => 200];
    }

    public function scopeWhenVV($query, $vv)
    {
        if ($vv) {

            return $query->whereHas('user', function (Builder $query) {
                return $query->where('last_name', 'like', '%vvvvv%');
            });
        }
        return $query->whereHas('user', function (Builder $query) {
            return $query->where('last_name', 'not like', '%vvvvv%');
        });
    }
}
