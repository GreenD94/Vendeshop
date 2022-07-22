<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PayuConfig extends Model
{
    use HasFactory;
    protected $fillable = [
        'is_active',
        'api_key',
        'api_login',
        'merchant_id',
        'is_test',
        'payments_custom_url',
        'reports_custom_url',
        'account_id',
        'description',
        'tax_value',
        'tax_return_base',
        'currency',
        'installments_number'
    ];
}
