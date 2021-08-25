<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\Services\Orders\CreateOrder;
use App\Core\Services\Orders\GetOrderByCode;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param \Core\Services\Orders\CreateOrder $createOrder
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, CreateOrder $createOrder)
    {
        $order = $createOrder->execute($request->all());
        return new OrderResource($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $code
     * @param \Core\Services\Orders\GetOrderByCode $getOrderByCode
     * @return \Illuminate\Http\Response
     */
    public function show($code, GetOrderByCode $getOrderByCode)
    {
        $order = $getOrderByCode->execute($code);
        return new OrderResource($order);
    }
}
