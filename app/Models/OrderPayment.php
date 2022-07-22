<?php

namespace App\Models;

use App\Http\Resources\OrderResource;
use App\Http\Resources\PushNotificationEventResource;
use App\Http\Resources\PushNotificationResource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection as SupportCollection;

class OrderPayment extends Model
{
    use HasFactory;
    protected $table = 'order_payments';
    protected $fillable = [
        'quantity',
        'order_id',
        'description',
        'value',
        'reference_code',
        'tax_return_base',
        'currency',
        'buyer_id',
        'buyer_name',
        'buyer_email',
        'buyer_contact_phone',
        'buyer_dni',
        'buyer_street',
        'buyer_street_2',
        'buyer_city',
        'buyer_state',
        'buyer_country',
        'buyer_postal_code',
        'buyer_phone',
        'payer_id',
        'payer_name',
        'payer_email',
        'payer_contact_phone',
        'payer_dni',
        'payer_street',
        'payer_street_2',
        'payer_city',
        'payer_state',
        'payer_country',
        'payer_postal_code',
        'payer_phone',
        'payment_method',
        'country',
        'payu_order_id',
        'transaction_id',
        'payu_state',
        'payment_network_response_code',
        'trazability_code',
        'response_code'
    ];
}
