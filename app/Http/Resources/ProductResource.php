<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'          => $this->id,
            'name'       => $this->name,
            'price'       => $this->price,
            'category_id' => $this->category_id,
            'image' => $this->image,
            'created_at'  => $this->created_at->format('d-m-Y')
        ];
    }
}
