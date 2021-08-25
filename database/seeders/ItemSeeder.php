<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Category;

class ItemSeeder extends Seeder
{
    private $items = [
        'Burgers' => [
            [
                'name' => 'Hotdog',
                'image' => 'images/hotdog.png',
                'price' => 35,
                'tax' => 12
            ],
            [
                'name' => 'Cheese Burger',
                'image' => 'images/cheese-burger.png',
                'price' => 43,
                'tax' => 8

            ],
            [
                'name' => 'Fries',
                'image' => 'images/fries.jpeg',
                'price' => 25,
                'tax' => 10
            ]
        ],
        'Beverages' => [
            [
                'name' => 'Coke',
                'image' => 'images/coke.png',
                'price' => 15,
                'tax' => 7
            ],
            [
                'name' => 'Sprite',
                'image' => 'images/sprite.jpeg',
                'price' => 15,
                'tax' => 7
            ],
            [
                'name' => 'Tea',
                'image' => 'images/tea.jpeg',
                'price' => 12,
                'tax' => 5
            ]
        ],
        'Combo Meals' => [
            [
                'name' => 'Chicken Combo Meal',
                'image' => 'images/chicken-combo-meal.png',
                'price' => 55,
                'tax' => 13,
            ],
            [
                'name' => 'Pork Combo',
                'image' => 'images/pork-combo.jpeg',
                'price' => 45,
                'tax' => 12,
            ],
            [
                'name' => 'Fish Combo',
                'image' => 'images/fish-combo.png',
                'price' => 45,
                'tax' => 11,
            ]
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->items as $categoryName => $items) {
            $category = Category::create(['name' => $categoryName]);
            $category->items()->createMany($items);
        }
    }
}
