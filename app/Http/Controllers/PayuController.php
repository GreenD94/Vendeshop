<?php

namespace App\Http\Controllers;

use App\Http\Requests\ad\AdDestroyRequest;
use App\Http\Requests\ad\AdIndexRequest;
use App\Http\Requests\ad\AdStoreRequest;
use App\Http\Requests\ad\AdUpdateRequest;
use App\Http\Resources\AdResource;
use App\Models\Ad;
use App\Models\Image;
use App\Models\Order;
use App\Models\OrderPayment;
use App\Models\OrderStatus;
use App\Models\PayuConfig;
use App\Traits\Responser;
use Carbon\Carbon;
use Environment;
use Illuminate\Http\Request;
use PayU;
use PayUCountries;
use PayUParameters;
use PayUPayments;
use SupportedLanguages;

class PayuController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(request $request)
    {
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(request $request)
    {
        $dates = explode('/', $request->date);
        $year = $dates[0];
        $month = $dates[1];
        $month = (string) ($month);

        // if (strlen($year) != 4) $this->errorResponse($request->date, "el año " . $year . " debe ser 4 digitos", 422);
        $data = [
            "credit_card_number" => (int)$request->credit_card_number,
            "date" => $year . '/' . $month, //"2030" . '/' . $month,
            "credit_card_security_code" => (int)$request->credit_card_security_code,
            "payment_method" => $request->payment_method ?? "VISA"
        ];

        if ($data["credit_card_number"] == 0) return $this->errorResponse($request->credit_card_number, "el numero de la tarjeta de credito no puede ser 0", 422);
        if ($data["credit_card_security_code"] == 0) return  $this->errorResponse($request->credit_card_security_code, "el numero de seguridad no puede ser 0", 422);
        $payuConfig = PayuConfig::where('is_active', true)->first();


        try {
            PayU::$apiKey =  $payuConfig->api_key;
            PayU::$apiLogin = $payuConfig->api_login;
            PayU::$merchantId = $payuConfig->merchant_id;
            PayU::$language = SupportedLanguages::ES;
            PayU::$isTest = (bool) $payuConfig->is_test;


            Environment::setPaymentsCustomUrl((string) $payuConfig->payments_custom_url);
            // Reports URL
            Environment::setReportsCustomUrl((string) $payuConfig->reports_custom_url);
            $order = Order::find($request->order_id);



            $reference = 'payment_' . $order->id . strtotime("now");
            // $reference = "payment_test_00000431";
            $value =  (float) $order->total;

            $parameters = array(
                // Enter the account’s identifier here
                PayUParameters::ACCOUNT_ID => $payuConfig->account_id,
                // Enter the reference code here.
                PayUParameters::REFERENCE_CODE => $reference,
                // Enter the description here.
                PayUParameters::DESCRIPTION => $payuConfig->description,

                // -- Values --
                // Enter the value here.
                PayUParameters::VALUE => $value,
                // Enter the value of the IVA (Value Added Tax only valid for Colombia) of the transaction,
                // if no IVA is sent, the system applies 19% automatically. It can contain two decimal digits.
                // Example 19000.00. In case you don't have IVA, set 0.
                PayUParameters::TAX_VALUE => $payuConfig->tax_value,
                // Enter the value of the base value on which VAT (only valid for Colombia) is calculated.
                // In case you don't have IVA, set 0.
                PayUParameters::TAX_RETURN_BASE => $payuConfig->tax_return_base,
                // Enter the currency here.
                PayUParameters::CURRENCY => (string) $payuConfig->currency,


                // -- Buyer --
                // Enter the buyer Id here.
                PayUParameters::BUYER_ID => $request->buyer_id,
                // Enter the buyer's name here.
                PayUParameters::BUYER_NAME => $request->buyer_name,
                // Enter the buyer's e-mail here.
                PayUParameters::BUYER_EMAIL => $request->buyer_email,
                // Enter the buyer's contact phone here.
                PayUParameters::BUYER_CONTACT_PHONE => $request->buyer_contact_phone,
                // Enter the buyer's contact document here.
                PayUParameters::BUYER_DNI => $request->buyer_dni,
                // Enter the buyer's address here.
                PayUParameters::BUYER_STREET => $request->buyer_street,
                PayUParameters::BUYER_STREET_2 => $request->buyer_street_2,
                PayUParameters::BUYER_CITY => $request->buyer_city,
                PayUParameters::BUYER_STATE =>  $request->buyer_state,
                PayUParameters::BUYER_COUNTRY => $request->buyer_country,
                PayUParameters::BUYER_POSTAL_CODE => $request->buyer_postal_code,
                PayUParameters::BUYER_PHONE => $request->buyer_phone,

                // Enter the payer's ID here.
                PayUParameters::PAYER_ID => $request->payer_id,
                /// Enter the payer's name here
                PayUParameters::PAYER_NAME => $request->payer_name,
                // Enter the payer's e-mail here
                PayUParameters::PAYER_EMAIL => $request->payer_email,
                PayUParameters::PAYER_CONTACT_PHONE => $request->payer_contact_phone,
                PayUParameters::PAYER_DNI => $request->payer_dni,
                PayUParameters::PAYER_STREET => $request->payer_street,
                PayUParameters::PAYER_STREET_2 => $request->payer_street_2,
                PayUParameters::PAYER_CITY => $request->payer_city,
                PayUParameters::PAYER_STATE =>  $request->payer_state,
                PayUParameters::PAYER_COUNTRY => $request->payer_country,
                PayUParameters::PAYER_POSTAL_CODE => $request->payer_postal_code,
                PayUParameters::PAYER_PHONE => $request->payer_phone,

                // -- Credit card data --
                // Enter the number of the credit card here
                PayUParameters::CREDIT_CARD_NUMBER => $data["credit_card_number"],
                // Enter expiration date of the credit card here
                PayUParameters::CREDIT_CARD_EXPIRATION_DATE => (string)   $request->date,
                // Enter the security code of the credit card here
                PayUParameters::CREDIT_CARD_SECURITY_CODE => $data["credit_card_security_code"],
                // Enter the name of the credit card here
                PayUParameters::PAYMENT_METHOD => $data["payment_method"],



                // Enter the number of installments here.

                PayUParameters::INSTALLMENTS_NUMBER => $payuConfig->installments_number,
                // Enter the name of the country here.
                PayUParameters::COUNTRY => PayUCountries::CO,

            );

            $response = PayUPayments::doAuthorizationAndCapture($parameters);
        } catch (\Throwable $th) {
            return  $this->errorResponse($th->getTrace(), $th->getMessage(), 422);
        }

        $requestData = $request->only(
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
            'country'
        );

        if ($response->transactionResponse->state == "APPROVED") {
            $payuData = [
                "payu_order_id" => $response->transactionResponse->orderId,
                "transaction_id" => $response->transactionResponse->transactionId,
                "payu_state" => $response->transactionResponse->state,
                "payment_network_response_code" => $response->transactionResponse->paymentNetworkResponseCode,
                "trazability_code" => $response->transactionResponse->trazabilityCode,
                "response_code" => $response->transactionResponse->responseCode,

                "trazabilityCode" => $response->transactionResponse->trazabilityCode,
                "responseCode" => $response->transactionResponse->responseCode,


            ];
            $data2 = array_merge($payuData, $requestData);
            OrderPayment::create($data2);

            $order->addStatusLog(OrderStatus::find(OrderStatus::$LISTO));
            $order->addEarningsToUser();

            return  $this->successResponse($payuData, $response->transactionResponse->state, 200);
        }
        if ($response->transactionResponse->state == "DECLINED") {
            $payuData = [
                "payu_order_id" => $response?->transactionResponse?->orderId ?? "",
                "transaction_id" => $response?->transactionResponse?->transactionId ?? "",
                "payu_state" => $response?->transactionResponse?->state ?? "",
                "payment_network_response_code" => $response?->transactionResponse?->paymentNetworkResponseErrorMessage ?? "",
                "trazability_code" => $response?->transactionResponse?->trazabilityCode ?? "",
                "response_code" => $response?->transactionResponse?->responseCode ?? "",

                "payment_network_response_code" =>  $response?->transactionResponse?->payment_network_response_code ?? "",
                "trazability_code" => $response?->transactionResponse?->trazability_code ?? ""
            ];
            $data2 = array_merge($payuData, $requestData);
            OrderPayment::create($data2);

            return  $this->errorResponse($payuData, $response->transactionResponse->state, 450);
        }
        if ($response->transactionResponse->state == "ERROR") {
            $payuData = [
                "payu_order_id" => $response?->transactionResponse?->orderId ?? "",
                "transaction_id" => $response?->transactionResponse?->transactionId ?? "",
                "payu_state" => $response?->transactionResponse?->state ?? "",
                "payment_network_response_code" => $response?->transactionResponse?->paymentNetworkResponseErrorMessage ?? "",
                "trazability_code" => $response?->transactionResponse?->trazabilityCode ?? "",
                "response_code" => $response?->transactionResponse?->responseCode ?? "",

                "payment_network_response_code" =>  $response?->transactionResponse?->payment_network_response_code ?? "",
                "trazability_code" => $response?->transactionResponse?->trazability_code ?? ""
            ];
            $data2 = array_merge($payuData, $requestData);
            OrderPayment::create($data2);

            return  $this->errorResponse($payuData, $response->transactionResponse->state, 451);
        }
        if ($response->transactionResponse->state == "EXPIRED") {
            $payuData = [
                "payu_order_id" => $response?->transactionResponse?->orderId ?? "",
                "transaction_id" => $response?->transactionResponse?->transactionId ?? "",
                "payu_state" => $response?->transactionResponse?->state ?? "",
                "payment_network_response_code" => $response?->transactionResponse?->paymentNetworkResponseErrorMessage ?? "",
                "trazability_code" => $response?->transactionResponse?->trazabilityCode ?? "",
                "response_code" => $response?->transactionResponse?->responseCode ?? "",

                "payment_network_response_code" =>  $response?->transactionResponse?->payment_network_response_code ?? "",
                "trazability_code" => $response?->transactionResponse?->trazability_code ?? ""
            ];
            $data2 = array_merge($payuData, $requestData);
            OrderPayment::create($data2);

            return  $this->errorResponse($payuData, $response->transactionResponse->state, 452);
        }
        if ($response->transactionResponse->state == "PENDING") {
            $payuData = [
                "payu_order_id" => $response?->transactionResponse?->orderId ?? "",
                "transaction_id" => $response?->transactionResponse?->transactionId ?? "",
                "payu_state" => $response?->transactionResponse?->state ?? "",
                "payment_network_response_code" => $response?->transactionResponse?->paymentNetworkResponseErrorMessage ?? "",
                "trazability_code" => $response?->transactionResponse?->trazabilityCode ?? "",
                "response_code" => $response?->transactionResponse?->responseCode ?? "",

                "payment_network_response_code" =>  $response?->transactionResponse?->payment_network_response_code ?? "",
                "trazability_code" => $response?->transactionResponse?->trazability_code ?? ""
            ];
            $data2 = array_merge($payuData, $requestData);
            OrderPayment::create($data2);

            return  $this->successResponse($payuData, $response->transactionResponse->state, 204);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(request $request)
    {
    }
}
