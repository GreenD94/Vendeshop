<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketConfig extends Model
{
    use HasFactory;
    protected $fillable = ['is_active', 'return_percentage', 'return_price', 'minimum_spend'];
}
