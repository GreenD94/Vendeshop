<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'quantity',
        'discount',
        'order_id',
        'price',
        'mock_price',
        'credits',
        'stock_id',
        'cover_image_id',
        'cover_image_url',
        'description',
        'name',
        'color_id',
        'color_hex',
        'size_id',
        'size_size',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'id');
    }
}
