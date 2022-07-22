<?php

namespace Database\Seeders;

use App\Models\PayuConfig;
use Illuminate\Database\Seeder;

class PayuConfigSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        PayuConfig::create([
            'is_active' => true,
            'api_key' => 'Ll7BS68n64bPeNV8kwrvvADt2b',
            'api_login' => 'e3TUApw72yLaH9Q',
            'merchant_id' => 911752,
            'is_test' => false,
            'payments_custom_url' => 'https://api.payulatam.com/payments-api/4.0/service.cgi',
            'reports_custom_url' => 'https://api.payulatam.com/reports-api/4.0/service.cgi',
            'account_id' => 918558,
            'description' => "cuenta principal",
            'tax_value' => "0",
            'tax_return_base' => "0",
            'currency' => 'COP',
            'installments_number' => "1"
        ]);
    }
}
