<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    use HasFactory;
    protected $with = ['status'];
    protected $fillable = ['order_status_id', 'order_id', 'order_status_name'];

    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id', 'id');
    }
}
