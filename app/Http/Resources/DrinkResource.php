<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DrinkResource extends JsonResource
{
public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'category_id' => $this->category_id,
            'category'    => $this->whenLoaded('category', function () {
                return [
                    'id'   => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),
            'name'        => $this->name,
            'unit_price'  => (float) $this->unit_price,
            'in_stock'    => (bool) $this->in_stock,

            // Smart Image Transformation:
            'image_url'   => $this->image
                ? (filter_var($this->image, FILTER_VALIDATE_URL)
                    ? $this->image
                    : asset('storage/' . $this->image))
                : null,

            'created_at'  => $this->created_at->toDateTimeString(),
        ];
    }
}
