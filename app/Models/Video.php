<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'url', 'is_information'];


    public function scopeWhenIsInformation($query, $is_information)
    {
        if ($is_information) $query->where('is_information', $is_information);
    }
}
