<?php

namespace App\Http\Resources;

use App\Models\ChaletFeatures;
use App\Models\ChaletImage;
use App\Models\ChaletPricing;
use App\Models\City;
use App\Models\Country;
use App\Models\Image;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChaletProfileResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'about' => $this->about,
            'user' => new UserMiniResource(User::find($this->user_id)),
            'image' => $this->image_id == null ? null : Image::find($this->image_id)->url,
            'reviews_count' => $this->reviews_count,
            'review' => $this->review,
            'views' => $this->views,
            'max_number' => $this->max_number,
            'balance' => $this->balance,
            'day_time' => $this->day_time,
            'night_time' => $this->night_time,
            'facebook' => $this->facebook,
            'instagram' => $this->instagram,
            'video' => $this->video,
            'address' => $this->address,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'is_special' => $this->is_special == 1 ? true : false,
            'is_blocked' => $this->is_blocked == 1 ? true : false,
            'is_approved' => $this->is_approved == 1 ? true : false,
            'down_payment_percent' => $this->down_payment_percent,
            'country' => new CountryResource(Country::find($this->country_id)),
            'city' => new CityResource(City::find($this->city_id)),
            'pricing' => ChaletPricing::where('chalet_id','=', $this->id)->first() == null ? null : new ChaletPricingResource(ChaletPricing::where('chalet_id','=', $this->id)->first()),
            'user_id_image' => $this->user_id_image_id == null ? null : Image::find($this->user_id_image_id)->url,
            'license' => $this->license == null ? null : Image::find($this->license)->url,
            'features' => $this->getFeatures($this->id),
            'images' => $this->getImages($this->id),
            'reviews' => ReviewResource::collection(Review::where('chalet_id','=',$this->id)->take(3)->get()),
        ];
    }

    private function getFeatures($id){
        $data = ChaletFeatures::where('chalet_id','=',$id)->get();

        return ChaletFeatureResource::collection($data);
    }

    private function getImages($id){
        $data = ChaletImage::where('chalet_id','=',$id)->get();

        return ChaletImageResource::collection($data);
    }
}
