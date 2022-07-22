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

        $usersId = User::role('admin')->pluck('id')->all();
        $maserId = User::role('master')->pluck('id')->all();
        $usersId = collect(array_merge($usersId, $maserId));
        if ($vv) $usersId = collect([]);
        if ($mainPost) {
            $replies = $mainPost->replies;
            $replies->push($mainPost);
            $usersId->concat($replies->pluck('user_id')->unique()->values()->all());
            $usersId->push($mainPost->user_id);
            $usersId = $usersId->unique()->values()->all();
        }
        $push_notification_event = PushNotificationEvent::find(PushNotificationEvent::$NEW_REPLY);

        $modelData = [
            "user_id" =>  $usersId,
            "tittle" => "new Reply",
            "body" =>  [
                "post" => new PostResource($this),
                'main_post_id' => $mainPost?->id,
                "event" => new PushNotificationEventResource($push_notification_event)
            ],
            "is_live" => $is_alive,
            "push_notification_event_id" => $push_notification_event->id,
        ];


        if ($is_alive) {
            $from = '';
            if ($this?->user?->first_name)  $from = $this?->user?->first_name . ': ';
            $tittle2 = $from . $this->body;
            $result = PushNotification::sendPushNotification($usersId, "Nuevo Mensaje",  $modelData['body'], true, $tittle2);
            $result = PushNotification::sendPushNotification($usersId, "Nuevo Mensaje",  $modelData['body'], false, $tittle2);
            if ($result["code"] != 200)  return ['data' => $result["data"], "message" => $result["message"], "code" =>  $result["code"]];
        }
        $modelData['body'] = json_encode($modelData['body']);
        $data = [];
        foreach ($usersId as $key => $id) {
            $modelData['user_id'] = $id;
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
