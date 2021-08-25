<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Coupon;

class CouponSeeder extends Seeder
{
    private $items = [
        [
            'code' => 'GO2018',
            'discount' => 10.00,
            'unit' => 'percentage'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->items as $item) {
            Coupon::create($item);
        }
    }
}
