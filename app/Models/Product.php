<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = "products";

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'size_subscriptions', 'product_id', 'size_id');
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class, 'color_subscriptions', 'product_id', 'color_id');
    }
}
