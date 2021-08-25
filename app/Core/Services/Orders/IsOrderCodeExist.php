<?php


namespace App\Core\Services\Orders;

use App\Models\Order;

class IsOrderCodeExist
{
    public function execute($code)
    {
        return Order::where('code', $code)->count() > 0;
    }
}