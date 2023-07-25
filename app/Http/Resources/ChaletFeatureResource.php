<?php

namespace App\Http\Resources;

use App\Models\Features;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChaletFeatureResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'feature_id' => $this->feature_id,
            'name_en' => Features::find($this->feature_id)->name_en,
            'name_ar' => Features::find($this->feature_id)->name_ar,
            'image' => Image::find(Features::find($this->feature_id)->image_id)->url,
        ];
    }
}
