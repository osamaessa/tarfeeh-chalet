<?php

namespace App\Http\Resources;

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChaletListItemResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'reviews_count' => $this->reviews_count,
            'review' => $this->review,
            'views' => $this->views,
            'image' => $this->image_id == null ? null : Image::find($this->image_id)->url
        ];
    }
}
