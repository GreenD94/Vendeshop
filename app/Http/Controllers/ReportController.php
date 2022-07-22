<?php

namespace App\Http\Controllers;

use App\Http\Requests\ad\AdIndexRequest;
use App\Http\Requests\report\ReportIndexRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\PaymentType;
use App\Traits\Responser;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    use Responser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReportIndexRequest $request)
    {

        $data = $request->only('days', 'months', 'years');
        $endDate = Carbon::now();
        $startDate = Carbon::now();
       
        if (!$data)   $startDate->subDays(7);
        if ($request->has('days'))  $startDate->subDays($request->days);
        if ($request->has('months'))  $startDate->subDays($request->months);
        if ($request->has('years'))  $startDate->subDays($request->years);
     

        $orderQuery = Order::whereDate('created_at', '>=', $startDate->format('Y-m-d'))
            ->whereDate('created_at', '<=', $endDate->format('Y-m-d'));
 
        $paymentTypes = PaymentType::all();
        $paymentTypes->push(PaymentType::factory()->make(['name' => 'todos']));
        $result = array();
        $paymentTypes->each(function ($paymentType, $key) use ($orderQuery, &$result) {

            $query = (clone $orderQuery)->whenPaymentTypeId($paymentType?->id);
     
            $result[$paymentType->name] =  $query->reportByStatus();
        });

        return $this->successResponse($result);
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
