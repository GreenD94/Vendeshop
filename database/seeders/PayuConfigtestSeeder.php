<?php

namespace Database\Seeders;

use App\Models\PayuConfig;
use Illuminate\Database\Seeder;

class PayuConfigtestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PayuConfig::create([
            'is_active' => false,
            'api_key' => 'Ll7BS68n64bPeNV8kwrvvADt2b',
            'api_login' => 'pRRXKOl8ikMmt9u',
            'merchant_id' => 508029,
            'is_test' => true,
            'payments_custom_url' => 'https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi',
            'reports_custom_url' => 'https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi',
            'account_id' => 512321,
            'description' => "cuenta principal testing",
            'tax_value' => "0",
            'tax_return_base' => "0",
            'currency' => 'COP',
            'installments_number' => "1"
        ]);
    }
}
