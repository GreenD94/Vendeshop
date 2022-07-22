<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comercial extends Model
{
    use HasFactory;
    protected $with = ['image'];
    protected $fillable = ['name',  'image_id'];
    static $WELCOME_NEW = 1;
    static $WELCOME_OLD = 2;
    public function image()
    {
        return $this->belongsTo(Image::class, 'image_id', 'id');
    }

    public function isWelcomeNew()
    {
        return $this->id == Comercial::$WELCOME_NEW;
    }
    public function isWelcomeOld()
    {
        return $this->id == Comercial::$WELCOME_OLD;
    }
}
