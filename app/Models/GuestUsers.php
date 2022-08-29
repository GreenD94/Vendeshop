<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuestUsers extends Model
{
    use HasFactory;
    protected $fillable = ['device_key'];

    static public function sendNewsNotification($tittle, $body, $projectID, $googleAccesToken, $tittle2 = null, $imageUrl = null)
    {

        $guestUsers = GuestUsers::get();
        foreach ($guestUsers as $key => $user) {
            $result = PushNotification::sendPushNotification(
                $user->device_key,
                $tittle,
                $body,
                $projectID,
                $googleAccesToken,
                $tittle2,
                $imageUrl,
                1
            );

            if ($result['code'] == 500) GuestUsers::destroy($user->id);
        }
    }
}
