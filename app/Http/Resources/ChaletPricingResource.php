<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChaletPricingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'sunday_to_wednesday_day' => $this->sunday_to_wednesday_day,
            'sunday_to_wednesday_night' => $this->sunday_to_wednesday_night,
            'saturday_and_thursday_day' => $this->saturday_and_thursday_day,
            'saturday_and_thursday_night' => $this->saturday_and_thursday_night,
            'friday_day' => $this->friday_day,
            'friday_night' => $this->friday_night,
            'full_day_extra_price' => $this->full_day_extra_price,
        ];
    }
}
