<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ribbon extends Model
{
    use HasFactory;
    protected $with = ["image"];
    protected $fillable = ['image_id'];
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }

    public function stocks()
    {
        return $this->hasMany(Stock::class, 'ribbon_id', 'id');
    }
}
