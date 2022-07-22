<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PushNotificationEvent extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    static $NEWS = 1;
    static $NEW_ORDER = 2;
    static $ORDER_STATE_CHANGE = 3;
    static $NEW_REPLY = 4;

    public function isNews()
    {
        return $this->id == PushNotificationEvent::$NEWS;
    }
    public function isNewOrder()
    {
        return $this->id == PushNotificationEvent::$NEW_ORDER;
    }
    public function isOrderstateChange()
    {
        return $this->id == PushNotificationEvent::$ORDER_STATE_CHANGE;
    }
    public function isNewReply()
    {
        return $this->id == PushNotificationEvent::$NEW_REPLY;
    }
}
