<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\CityResource;
use App\Http\Resources\CountriesAndCitiesResource;
use App\Http\Resources\CountryResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Image;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    
    use ErrorResponseTrait;

    public function addCountry(Request $request)
    {
        try {

            $fields = $request->validate([
                'name_en' => 'required',
                'name_ar' => 'required',
                'phone_code' => 'required',
                'currency' => 'required',
                'iso' => 'required',
                'image_id' => 'required',
            ]);

            $image = Image::find($fields['image_id']);
            if (!$image) {
                return $this->badRequest(Messages::IMAGE_NOT_FOUND);
            }

            $country = Country::create([
                'name_en' => $fields['name_en'],
                'name_ar' => $fields['name_ar'],
                'phone_code' => $fields['phone_code'],
                'currency' => $fields['currency'],
                'iso' => $fields['iso'],
                'image_id' => $fields['image_id'],
            ]);


            return new CountryResource($country);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function addCity(Request $request)
    {
        try {

            $fields = $request->validate([
                'name_en' => 'required',
                'name_ar' => 'required',
                'country_id' => 'required',
            ]);

            $country = Country::find($fields['country_id']);
            if (!$country) {
                return $this->badRequest(Messages::COUNTRY_NOT_FOUND);
            }

            $city = City::create([
                'name_en' => $fields['name_en'],
                'name_ar' => $fields['name_ar'],
                'country_id' => $fields['country_id'],
            ]);

            return new CityResource($city);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {

            $data = Country::all();

            return CountriesAndCitiesResource::collection($data);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
}
