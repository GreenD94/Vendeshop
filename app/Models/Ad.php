<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    use HasFactory;
    protected $with = ['image'];
    protected $fillable = ['name', 'is_favorite', 'color', 'image_id'];
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }
}
