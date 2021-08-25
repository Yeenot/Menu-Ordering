<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ItemResource;
use App\Http\Resources\CouponResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Possible to attach additional fields
        return array_merge(
            parent::toArray($request),
            [
                'items' => ItemResource::collection($this->whenLoaded('items')),
                'coupon' => new CouponResource($this->whenLoaded('coupon'))
            ]
        );
    }
}
