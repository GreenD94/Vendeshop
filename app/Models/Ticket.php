<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'value',
        'expiration_time',
        'is_used',
        'is_active',
    ];

    public function scopeWhenUserId($query, $user_id)
    {
        if ($user_id) $query->where('user_id', $user_id);
    }
}
