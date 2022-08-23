<?php

use App\Http\Controllers\AdController;
use App\Http\Controllers\address\OfficeAddressController;
use App\Http\Controllers\address\UserAddressController;
use App\Http\Controllers\addressController;
use App\Http\Controllers\AuthsController;
use App\Http\Controllers\BackgroundController;
use App\Http\Controllers\BannersController;
use App\Http\Controllers\BootsController;
use App\Http\Controllers\branch_office\BranchOfficesController;
use App\Http\Controllers\CategoriesController as ControllersCategoriesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\category_subscription\CategorySubscriptionsController;
use App\Http\Controllers\chat\ChatsController;
use App\Http\Controllers\color\OfficeColorController;
use App\Http\Controllers\color_tag\OfficeColorRolesController;
use App\Http\Controllers\ColorsController;
use App\Http\Controllers\ComercialController;
use App\Http\Controllers\company\CompaniesController;
use App\Http\Controllers\currency\CurrenciesController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\IconCategoryController;
use App\Http\Controllers\IconController;
use App\Http\Controllers\image\OfficeImageController;
use App\Http\Controllers\image\ProductImageController;
use App\Http\Controllers\image\UserImageController;
use App\Http\Controllers\image_tag\OfficeImageRolesController;
use App\Http\Controllers\image_tag\ProductImageRolesController;
use App\Http\Controllers\image_tag\UserImageRolesController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\login\AppLoginsController;
use App\Http\Controllers\login\LoginsController;
use App\Http\Controllers\office_subscription\OfficeSubscriptionsController;
use App\Http\Controllers\office_subscription_status\OfficeSubscriptionStatusController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\PaymentTypeController;
use App\Http\Controllers\PayuConfigController;
use App\Http\Controllers\PayuController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\product\ProductDetailsController;
use App\Http\Controllers\product\ProductsController;
use App\Http\Controllers\PushNotificationController;
use App\Http\Controllers\PushNotificationEventsController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RibbonsController;
use App\Http\Controllers\role\RolesController;
use App\Http\Controllers\role_subscription\RoleSubscriptionsController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ShippingCostController;
use App\Http\Controllers\TicketConfigController;
use App\Http\Controllers\SizesController;
use App\Http\Controllers\StockFavoriteController;
use App\Http\Controllers\StockImageSubscription;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\tokens\TokensController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VideosController;
use App\Http\Resources\ShippingCostResource;
use App\Models\Order;
use App\Models\PayuConfig;
use App\Models\ProductDetail;
use App\Models\ShippingCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/




Route::group(['middleware' => 'EnsureTokenIsAuth'], function () {
    Route::get('mobile/auth', [AuthsController::class, 'index'])->name('api.mobile.auth.index');


    Route::get('mobile/reports', [ReportController::class, 'index'])->name('api.mobile.reports.index');


    Route::post('mobile/push-notification', [PushNotificationController::class, 'store'])->name('api.mobile.push-notification.store');

    Route::get('mobile/users', [UsersController::class, 'index'])->name('api.mobile.users.index');

    Route::put('mobile/users', [UsersController::class, 'update'])->name('api.mobile.users.update');
    Route::delete('mobile/users', [UsersController::class, 'destroy'])->name('api.mobile.users.destroy');


    Route::get('mobile/payment-type', [PaymentTypeController::class, 'index'])->name('api.mobile.payment_type.index');
    Route::delete('mobile/payment-type', [PaymentTypeController::class, 'destroy'])->name('api.mobile.payment_type.destroy');
    Route::post('mobile/payment-type', [PaymentTypeController::class, 'store'])->name('api.mobile.payment_type.store');
    Route::put('mobile/payment-type', [PaymentTypeController::class, 'update'])->name('api.mobile.payment_type.update');


    Route::get('mobile/images', [ImagesController::class, 'index'])->name('api.mobile.images.index');
    Route::delete('mobile/images', [ImagesController::class, 'destroy'])->name('api.mobile.images.destroy');
    Route::post('mobile/images', [ImagesController::class, 'store'])->name('api.mobile.images.store');
    Route::put('mobile/images', [ImagesController::class, 'update'])->name('api.mobile.images.Update');

    Route::get('mobile/tickets', [TicketController::class, 'index'])->name('api.mobile.tickets.index');
    Route::delete('mobile/tickets', [TicketController::class, 'destroy'])->name('api.mobile.tickets.destroy');
    Route::post('mobile/tickets', [TicketController::class, 'store'])->name('api.mobile.tickets.store');
    Route::put('mobile/tickets', [TicketController::class, 'update'])->name('api.mobile.tickets.update');


    Route::get('mobile/colors', [ColorsController::class, 'index'])->name('api.mobile.colors.index');
    Route::delete('mobile/colors', [ColorsController::class, 'destroy'])->name('api.mobile.colors.destroy');
    Route::post('mobile/colors', [ColorsController::class, 'store'])->name('api.mobile.colors.store');
    Route::put('mobile/colors', [ColorsController::class, 'update'])->name('api.mobile.colors.update');


    Route::get('mobile/shipping-cost', [ShippingCostController::class, 'index'])->name('api.mobile.ShippingCost.index');
    Route::delete('mobile/shipping-cost', [ShippingCostController::class, 'destroy'])->name('api.mobile.ShippingCost.destroy');
    Route::post('mobile/shipping-cost', [ShippingCostController::class, 'store'])->name('api.mobile.ShippingCost.store');
    Route::put('mobile/shipping-cost', [ShippingCostController::class, 'update'])->name('api.mobile.ShippingCost.update');

    Route::get('mobile/ticket-config', [TicketConfigController::class, 'index'])->name('api.mobile.TicketConfig.index');
    Route::delete('mobile/ticket-config', [TicketConfigController::class, 'destroy'])->name('api.mobile.TicketConfig.destroy');
    Route::post('mobile/ticket-config', [TicketConfigController::class, 'store'])->name('api.mobile.TicketConfig.store');
    Route::put('mobile/ticket-config', [TicketConfigController::class, 'update'])->name('api.mobile.TicketConfig.update');



    Route::get('mobile/sizes', [SizesController::class, 'index'])->name('api.mobile.sizes.index');
    Route::delete('mobile/sizes', [SizesController::class, 'destroy'])->name('api.mobile.sizes.destroy');
    Route::post('mobile/sizes', [SizesController::class, 'store'])->name('api.mobile.sizes.store');
    Route::put('mobile/sizes', [SizesController::class, 'update'])->name('api.mobile.sizes.update');




    Route::get('mobile/banners', [BannersController::class, 'index'])->name('api.mobile.banners.index');
    Route::delete('mobile/banners', [BannersController::class, 'destroy'])->name('api.mobile.banners.destroy');
    Route::post('mobile/banners', [BannersController::class, 'store'])->name('api.mobile.banners.store');
    Route::put('mobile/banners', [BannersController::class, 'update'])->name('api.mobile.banners.update');

    Route::get('mobile/payuconfig', [PayuConfigController::class, 'index'])->name('api.mobile.payuconfig.index');
    Route::delete('mobile/payuconfig', [PayuConfigController::class, 'destroy'])->name('api.mobile.payuconfig.destroy');
    Route::post('mobile/payuconfig', [PayuConfigController::class, 'store'])->name('api.mobile.payuconfig.store');
    Route::put('mobile/payuconfig', [PayuConfigController::class, 'update'])->name('api.mobile.payuconfig.update');




    Route::get('mobile/order', [OrderController::class, 'index'])->name('api.mobile.order.index');
    Route::delete('mobile/order', [OrderController::class, 'destroy'])->name('api.mobile.order.destroy');
    Route::post('mobile/order', [OrderController::class, 'store'])->name('api.mobile.order.store');
    Route::put('mobile/order', [OrderController::class, 'update'])->name('api.mobile.order.update');


    Route::get('mobile/order-status', [OrderStatusController::class, 'index'])->name('api.mobile.order_status.index');
    Route::delete('mobile/order-status', [OrderStatusController::class, 'destroy'])->name('api.mobile.order_status.destroy');
    Route::post('mobile/order-status', [OrderStatusController::class, 'store'])->name('api.mobile.order_status.store');
    Route::put('mobile/order-status', [OrderStatusController::class, 'update'])->name('api.mobile.order_status.update');



    Route::delete('mobile/categories', [CategoriesController::class, 'destroy'])->name('api.mobile.categories.destroy');
    Route::post('mobile/categories', [CategoriesController::class, 'store'])->name('api.mobile.categories.store');
    Route::put('mobile/categories', [CategoriesController::class, 'update'])->name('api.mobile.categories.update');



    // Route::get('mobile/stock/image-subscription', [StockImageSubscription::class, 'index'])->name('api.mobile.stock.imagesubscription.index');
    Route::delete('mobile/stock/image-subscription', [StockImageSubscription::class, 'destroy'])->name('api.mobile.stock.imagesubscription.destroy');
    Route::post('mobile/stock/image-subscription', [StockImageSubscription::class, 'store'])->name('api.mobile.stock.imagesubscription.store');
    // Route::put('mobile/stock/image-subscription', [StockImageSubscription::class, 'update'])->name('api.mobile.stock.imagesubscription.update');




    Route::get('mobile/ribbons', [RibbonsController::class, 'index'])->name('api.mobile.ribbons.index');
    Route::post('mobile/ribbons', [RibbonsController::class, 'store'])->name('api.mobile.ribbons.store');
    Route::put('mobile/ribbons', [RibbonsController::class, 'update'])->name('api.mobile.ribbons.update');
    Route::delete('mobile/ribbons', [RibbonsController::class, 'destroy'])->name('api.mobile.ribbons.destroy');


    Route::post('mobile/icons', [IconController::class, 'store'])->name('api.mobile.icons.store');
    Route::put('mobile/icons', [IconController::class, 'update'])->name('api.mobile.icons.update');
    Route::delete('mobile/icons', [IconController::class, 'destroy'])->name('api.mobile.icons.destroy');


    Route::post('mobile/icon-category', [IconCategoryController::class, 'store'])->name('api.mobile.icon_category.store');
    Route::put('mobile/icon-category', [IconCategoryController::class, 'update'])->name('api.mobile.icon_category.update');
    Route::delete('mobile/icon-category', [IconCategoryController::class, 'destroy'])->name('api.mobile.icon_category.destroy');


    Route::post('mobile/ads', [AdController::class, 'store'])->name('api.mobile.ads.store');
    Route::put('mobile/ads', [AdController::class, 'update'])->name('api.mobile.ads.update');
    Route::delete('mobile/ads', [AdController::class, 'destroy'])->name('api.mobile.ads.destroy');

    Route::post('mobile/backgrounds', [BackgroundController::class, 'store'])->name('api.mobile.backgrounds.store');
    Route::put('mobile/backgrounds', [BackgroundController::class, 'update'])->name('api.mobile.backgrounds.update');
    Route::delete('mobile/backgrounds', [BackgroundController::class, 'destroy'])->name('api.mobile.backgrounds.destroy');



    Route::post('mobile/stocks', [StocksController::class, 'store'])->name('api.mobile.stocks.store');
    Route::put('mobile/stocks', [StocksController::class, 'update'])->name('api.mobile.stocks.update');
    Route::delete('mobile/stocks', [StocksController::class, 'destroy'])->name('api.mobile.stocks.destroy');

    Route::put('mobile/stocks/favorites', [StockFavoriteController::class, 'update'])->name('api.mobile.stock.favorite.update');



    Route::delete('mobile/auth', [AuthsController::class, 'destroy'])->name('api.mobile.auth.destroy');

    Route::get('mobile/push-notification-events', [PushNotificationEventsController::class, 'index'])->name('api.mobile.push_notification_events.index');
    Route::post('mobile/push-notification-events', [PushNotificationEventsController::class, 'store'])->name('api.mobile.push_notification_events.store');
    Route::put('mobile/push-notification-events', [PushNotificationEventsController::class, 'update'])->name('api.mobile.push_notification_events.update');
    Route::delete('mobile/push-notification-events', [PushNotificationEventsController::class, 'destroy'])->name('api.mobile.push_notification_events.destroy');


    Route::get('mobile/push_notifications', [PushNotificationController::class, 'index'])->name('api.mobile.push_notifications.index');
    Route::post('mobile/push_notifications', [PushNotificationController::class, 'store'])->name('api.mobile.push_notifications.store');
    Route::put('mobile/push_notifications', [PushNotificationController::class, 'update'])->name('api.mobile.push_notifications.update');
    Route::delete('mobile/push_notifications', [PushNotificationController::class, 'destroy'])->name('api.mobile.push_notifications.destroy');


    Route::get('mobile/comercials', [ComercialController::class, 'index'])->name('api.mobile.comercials.index');
    Route::post('mobile/comercials', [ComercialController::class, 'store'])->name('api.mobile.comercials.store');
    Route::put('mobile/comercials', [ComercialController::class, 'update'])->name('api.mobile.comercials.update');
    Route::delete('mobile/comercials', [ComercialController::class, 'destroy'])->name('api.mobile.comercials.destroy');



    Route::post('mobile/videos', [VideosController::class, 'store'])->name('api.mobile.videos.store');
    Route::put('mobile/videos', [VideosController::class, 'update'])->name('api.mobile.videos.update');
    Route::delete('mobile/videos', [VideosController::class, 'destroy'])->name('api.mobile.videos.destroy');

    Route::get('mobile/posts', [PostController::class, 'index'])->name('api.mobile.posts.index');
    Route::post('mobile/posts', [PostController::class, 'store'])->name('api.mobile.posts.store');
    Route::put('mobile/posts', [PostController::class, 'update'])->name('api.mobile.posts.update');
    Route::delete('mobile/posts', [PostController::class, 'destroy'])->name('api.mobile.posts.destroy');

    Route::get('mobile/address', [addressController::class, 'index'])->name('api.mobile.address.index');
    Route::post('mobile/address', [addressController::class, 'store'])->name('api.mobile.address.store');
    Route::put('mobile/address', [addressController::class, 'update'])->name('api.mobile.address.update');
    Route::delete('mobile/address', [addressController::class, 'destroy'])->name('api.mobile.address.destroy');
    Route::get('mobile/roles', [RoleController::class, 'index'])->name('api.mobile.roles.index');
    Route::post('mobile/send-email', [EmailController::class, 'store'])->name("api.mobile.send_email.store");
});


Route::group(['middleware' => 'EnsureTokenIsValid'], function () {
    Route::get('mobile/boots', [BootsController::class, 'index'])->name('api.mobile.boots.index');
    Route::get('mobile/stocks', [StocksController::class, 'index'])->name('api.mobile.stocks.index');
    Route::post('mobile/users', [UsersController::class, 'store'])->name('api.mobile.users.store');
});

//------------------------------------------------
Route::get('mobile/categories', [CategoriesController::class, 'index'])->name('api.mobile.categories.index');

Route::post('mobile/auth', [AuthsController::class, 'store'])->name('api.mobile.auth.store');
//Route::get('mobile/refresh', [BootsController::class, 'refresh'])->name('api.mobile.boots.refresh');
Route::get('mobile/icons', [IconController::class, 'index'])->name('api.mobile.icons.index');
Route::get('mobile/icon-category', [IconCategoryController::class, 'index'])->name('api.mobile.icon_category.index');
Route::get('mobile/ads', [AdController::class, 'index'])->name('api.mobile.ads.index');
Route::get('mobile/backgrounds', [BackgroundController::class, 'index'])->name('api.mobile.backgrounds.index');
Route::get('mobile/posts', [PostController::class, 'index'])->name('api.mobile.posts.index');
Route::get('mobile/videos', [VideosController::class, 'index'])->name('api.mobile.videos.index');



Route::post('/payu-payment-test', function (Request $request) {
    $month = "10";
    $data = [
        "credit_card_number" => (int)$request->credit_card_number,
        "date" => "2030" . '/' . $month,
        "credit_card_security_code" => (int)$request->credit_card_security_code,
        "payment_method" => $request->payment_method ?? "VISA"
    ];

    if ($data["credit_card_number"] == 0) return $data["error"] = "el numero de la tarjeta de credito no puede ser 0";
    if ($data["credit_card_security_code"] == 0) return $data["error"] = "el numero de seguridad no puede ser 0";
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
        $value = 38050.00;

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
            PayUParameters::CREDIT_CARD_EXPIRATION_DATE => (string) $request->date,
            // Enter the security code of the credit card here
            PayUParameters::CREDIT_CARD_SECURITY_CODE => $data["credit_card_security_code"],
            // Enter the name of the credit card here
            PayUParameters::PAYMENT_METHOD => $data["payment_method"],



            // Enter the number of installments here.

            PayUParameters::INSTALLMENTS_NUMBER => $payuConfig->installments_number,
            // Enter the name of the country here.
            PayUParameters::COUNTRY => PayUCountries::CO,

            // Device Session ID
            //PayUParameters::DEVICE_SESSION_ID => "vghs6tvkcle931686k1900o6e1",
            // Payer IP
            //  PayUParameters::IP_ADDRESS => "127.0.0.1",
            // Cookie of the current session
            // PayUParameters::PAYER_COOKIE => "pt1t38347bs6jc9ruv2ecpv7o2",
            // User agent of the current session
            // PayUParameters::USER_AGENT => "Mozilla/5.0 (Windows NT 5.1; rv:18.0) Gecko/20100101 Firefox/18.0"
        );

        // Authorization request
        $response = PayUPayments::doAuthorizationAndCapture($parameters);

        return $response;


        $data2 = [
            "orderId" => $response->transactionResponse->orderId,
            "transactionId" => $response->transactionResponse->transactionId,
            "state" => $response->transactionResponse->state,
            "paymentNetworkResponseCode" => $response->transactionResponse->paymentNetworkResponseCode,

            "trazabilityCode" => $response->transactionResponse->trazabilityCode,
            "responseCode" => $response->transactionResponse->responseCode,


        ];
        if ($response->transactionResponse->state == "APPROVED") {
        }
        if ($response->transactionResponse->state == "DECLINED") {
        }
        if ($response->transactionResponse->state == "ERROR") {
        }
        if ($response->transactionResponse->state == "EXPIRED") {
        }
        if ($response->transactionResponse->state == "PENDING") {
        }
    } catch (\Throwable $th) {

        return  dd("catch", ["message" => $th->getMessage(), $data]);
    }
})->name('payu.payment.test');

Route::post('/payu-payment', [PayuController::class, 'store'])->name('api.mobile.payu.store');


Route::get('/test', function (Request $request) {

    $data = preg_replace('/[0-9]+/', '', $request->formatted_address);
    $data = collect(explode(",", $data));
    $data = $data->map(function ($item, $key) {
        return  trim($item);
    });
    $query = ShippingCost::findFromGoogleMaps($data);
    return  response()->json([
        //'code'		=>	200,
        'message'   => "test",
        'data'      => ShippingCostResource::collection($query->get())
    ], 200);
});
