<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CouponResource;
use App\Core\Services\Coupons\GetCouponByCode;

class CouponController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  string  $code
     * @param \Core\Services\Coupons\GetCouponByCode $getCouponByCode
     * @return \Illuminate\Http\Response
     */
    public function show($code, GetCouponByCode $getCouponByCode)
    {
        $coupon = $getCouponByCode->execute($code);
        return new CouponResource($coupon);
    }
}
