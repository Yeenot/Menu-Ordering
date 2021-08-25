<?php

namespace App\Core\Services\Orders;

use App\Models\Order;

class GetOrderByCode
{
    public function execute($code)
    {
        $order = Order::with('items', 'coupon')->where('code', $code)->first();
        return $order;
    }
}