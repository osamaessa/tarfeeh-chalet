<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountriesAndCitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name_en' => $this->name_en,
            'name_ar' => $this->name_ar,
            'iso' => $this->iso,
            'currency' => $this->currency,
            'image' => Image::find($this->image_id)->url,
            'phone_code' => $this->phone_code,
            'cities' => CityResource::collection(City::where('country_id','=',$this->id)->get())
        ];
    }
}
