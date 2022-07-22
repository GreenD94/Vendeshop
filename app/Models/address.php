<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class address extends Model
{
    use HasFactory;
    protected $fillable = [
        'address',
        'city_id',
        'city_name',
        'street',
        'postal_code',
        'deparment',
        'phone_number',
        'is_favorite',
        'state_name',
        'state_id'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_address', 'address_id', 'user_id');
    }

    public function scopeWhenUserId($query, $user_id)
    {

        if ($user_id)
            $query->whereHas('users', function (Builder $query2) use ($user_id) {
                $query2->where('users.id',  $user_id);
            });
    }
}
