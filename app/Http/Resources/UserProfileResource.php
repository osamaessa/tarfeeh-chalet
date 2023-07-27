<?php

namespace App\Http\Resources;

use App\Models\City;
use App\Models\Country;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'type' => $this->type,
            'image' => $this->image_id == null ? null : Image::find($this->image_id)->url,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_blocked' => $this->is_blocked == 1 ? true : false,
            'reports_count' => $this->reports_count,
            'city' => new CityResource(City::find($this->city_id)),
            'country' => new CountryResource(Country::find($this->country_id)),
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
        ];
    }
}
