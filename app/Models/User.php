<?php

namespace App\Models;

use App\Http\Resources\ComercialResource;
use App\Traits\Query;
use AppleSignIn\ASDecoder;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Query;
    use HasFactory, Notifiable, HasApiTokens;
    use HasRoles;

    static public $ADMIN_ROLE = "admin";
    static public $CUSTOMER_ROLE = "customer";
    static public $MASTER_ROLE = "master";


    protected $with = ['avatar', 'roles', 'tickets', 'addresses'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'birth_date',
        'device_key',
        'avatar_id',
        'city',
        'address',
        'dni'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function avatar()
    {
        return $this->belongsTo(Image::class, 'avatar_id', 'id');
    }

    static public function StoreAuth($email, $password, $device_key)
    {
        $user = User::where('email', $email)->first();
        if ($device_key) $user->device_key = $device_key;
        if ($device_key) $user->save();
        if (!$user || !Hash::check($password, $user->password)) {
            return false;
        }
        $result = ['token' => $user->createToken($user->id . $user->name . uniqid())->plainTextToken, "welcome" => new ComercialResource(Comercial::find(Comercial::$WELCOME_OLD))];
        if ($user->is_new) {
            $result['welcome'] = new ComercialResource(Comercial::find(Comercial::$WELCOME_NEW));
            $user->is_new = false;
            $user->save();
        }
        return $result;
    }

    static public function StoreGoogleAuth($device_key, $token)
    {

        $client = new \Google_Client();
        $payload = null;

        $payload =   $client->verifyIdToken($token);

        if ($payload) {

            $googleUser =
                [
                    "name" => $payload["name"],
                    "email" => $payload["email"],
                    "device_key" => $device_key
                ];


            $user = User::where('email', $googleUser["email"])->first();
            if ($user) $user->device_key = $googleUser["device_key"];
            if ($user) $user->save();
            if (!$user) $user = User::create([
                'first_name' => $googleUser["name"],
                'last_name' => "",
                'email' => $googleUser["email"],
                "password" => Hash::make("google|" . $googleUser["email"]),
                'device_key' => $googleUser["device_key"],

            ]);
            $result = ['token' => $user->createToken($user->id . $user->name . uniqid())->plainTextToken, "welcome" => new ComercialResource(Comercial::find(Comercial::$WELCOME_OLD))];
            if ($user->is_new) {
                $result['welcome'] = new ComercialResource(Comercial::find(Comercial::$WELCOME_NEW));
                $user->is_new = false;
                $user->save();
            }
            return $result;
        }

        return false;
    }

    static public function StoreAppleAuth($device_key, $token)
    {


        $payload =  ASDecoder::getAppleSignInPayload($token);


        if ($payload) {
            $appleuser =
                [
                    "name" => strstr($payload->getEmail(), '@', true),
                    "email" => $payload->getEmail(),
                    "device_key" => $device_key
                ];


            $user = User::where('email', $appleuser["email"])->first();
            if ($user) $user->device_key = $appleuser["device_key"];
            if ($user) $user->save();
            if (!$user) $user = User::create([
                'first_name' => $appleuser["name"],
                'last_name' => "",
                'email' => $appleuser["email"],
                "password" => Hash::make("apple|" . $appleuser["email"]),
                'device_key' => $appleuser["device_key"],

            ]);
            $result = ['token' => $user->createToken($user->id . $user->name . uniqid())->plainTextToken, "welcome" => new ComercialResource(Comercial::find(Comercial::$WELCOME_OLD))];
            if ($user->is_new) {
                $result['welcome'] = new ComercialResource(Comercial::find(Comercial::$WELCOME_NEW));
                $user->is_new = false;
                $user->save();
            }
            return $result;
        }
        return false;
    }

    public function favorite_stock()
    {
        return $this->belongsToMany(Stock::class, 'favorite_stocks',  'user_id', 'stock_id');
    }

    public function tickets()
    {

        return $this->hasMany(Ticket::class, 'user_id', 'id');
    }

    // public function notificationss()
    // {

    //     return $this->hasMany(PushNotification::class, 'user_id', 'id');
    // }

    public function unreadNotifications()
    {

        return PushNotification::where('user_id', $this->id)->where('is_new', true);
    }

    public function addresses()
    {
        return $this->belongsToMany(address::class, 'user_address', 'user_id', 'address_id');
    }

    public function scopeWhenRole($query, $role)
    {
        if ($role) {
            $query->role($role);
        }
    }


    public function scopeWhenId($query, $id)
    {
        if ($id)
            $query->where('id', $id);
    }

    public function scopeWhenVV($query, $vv)
    {
        if ($vv) {
            return $query->where('last_name', 'like', '%vvvvv%');
        }
        return $query->where('last_name', 'not like', '%vvvvv%');
    }


    public function addTickets($value)
    {
        if (!$value || $value <= 0) return;
        $createdModel = $this;
        return  $createdModel->tickets()->create(
            [

                'value' => (-1) * $value,
                'expiration_time' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }



    public function isFirstTimeBuyerTicketMaximumLimitExceeded($total, $ticket)
    {
        $isFirstTimeBuyer = Order::where('user_id', $this->id)->whenStatusId(OrderStatus::$LISTO)->count() == 0;
        if (!$isFirstTimeBuyer) return false;
        if ($ticket != 5000) return false;
        return $total < 8000;
    }


    static public function getFCMUsers($user_id)
    {
        if ($user_id == "*") return User::whereNotNull('device_key');
        return User::whereIn('id', $user_id)->whereNotNull('device_key');
    }

    public function isAdmin()
    {
        return $this->hasRole([User::$ADMIN_ROLE]);
    }
    public function isCustomer()
    {
        return $this->hasRole([User::$CUSTOMER_ROLE]);
    }
    public function isMaster()
    {
        return $this->hasRole([User::$MASTER_ROLE]);
    }


    public function sendResetBadgeNotification()
    {
        $googleAccessAdminToken = PushNotification::getGoogleAccessAdminToken();
        $googleAccessUserToken = PushNotification::getGoogleAccessUserToken();
        $unreadNotification = 0;
        PushNotification::sendPushNotification(
            $this->device_key,
            "Desconectado",
            "Hasta Pronto " . $this->first_name,
            PushNotification::$ADMIN_PROJECT_ID,
            $googleAccessAdminToken,
            null,
            null,
            $unreadNotification

        );

        PushNotification::sendPushNotification(
            $this->device_key,
            "Desconectado",
            "Hasta Pronto " . $this->first_name,
            PushNotification::$USER_PROJECT_ID,
            $googleAccessUserToken,
            null,
            null,
            $unreadNotification

        );
    }
}
