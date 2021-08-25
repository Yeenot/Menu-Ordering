<?php

namespace App\Core\Services\Orders;

use App\Models\Order;
use App\Models\Item;
use App\Core\Services\Orders\GenerateOrderCode;
use App\Core\Services\Coupons\GetCouponByCode;

class CreateOrder
{
    protected $generateOrderCode, $getCouponByCode;

    public function __construct(GenerateOrderCode $generateOrderCode, GetCouponByCode $getCouponByCode)
    {
        $this->generateOrderCode = $generateOrderCode;
        $this->getCouponByCode = $getCouponByCode;
    }

    public function execute($data)
    {
        // Structure items
        $orders = [];
        foreach($data['items'] as $item) {
            $orders[$item['id']] = $item;
        }

        // Get items
        $ids = array_keys($orders);
        $items = Item::whereIn('id', $ids)->get();

        // Get item details
        $total = 0;
        $orderItems = [];
        foreach ($items as $item) {
            $quantity = $orders[$item->id]['quantity'];
            $adjustedPrice = $item->price + ($item->price * ($item->tax/100));
            
            $subtotal = $adjustedPrice * $quantity;
            $total += $subtotal;

            $orderItems[$item->id] = [
                'name' => $item->name,
                'price' => $item->price,
                'tax' => $item->tax,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        };

        // Get coupon
        $coupon = null;
        if (isset($data['coupon'])) {
            $coupon = $this->getCouponByCode->execute($data['coupon']);
        }

        // Apply discount
        $discount = 0;
        if (!is_null($coupon)) {
            if ($coupon->unit === 'percentage') {
                $discount = $total * ($coupon->discount / 100);
            } else {
                $discount = $coupon->discount;
            }
        }

        // Assign order details
        $code = $this->generateOrderCode->execute();
        $orderDetails = [
            'code' => $code,
            'coupon_id' => $coupon->id ?? null,
            'discount_value' => $coupon->discount ?? 0,
            'discount_unit' => $coupon->unit ?? null,
            'total' => $total - $discount
        ];

        // Save
        $order = Order::create($orderDetails);
        $order->items()->attach($orderItems);

        return $order;
    }
}