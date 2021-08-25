<?php

namespace App\Core\Services\Coupons;

use App\Models\Coupon;

class GetCouponByCode
{
    public function execute($code)
    {
        $coupon = Coupon::where('code', $code)->first();

        if (is_null($coupon))
            abort(433);

        return $coupon;
    }
}