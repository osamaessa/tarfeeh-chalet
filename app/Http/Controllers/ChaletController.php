<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\ChaletListItemResource;
use App\Http\Resources\ChaletProfileResource;
use App\Models\Chalet;
use App\Models\ChaletPricing;
use App\Models\City;
use App\Models\Country;
use App\Models\Image;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;

class ChaletController extends Controller
{
    use ErrorResponseTrait;

    public function profile(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }
            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }
            if ($chalet->is_blocked) {
                return $this->badRequest(Messages::CHALET_BLOCKED);
            }

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function setup(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if ($chalet) {
                return $this->badRequest(Messages::CHALET_ALREADY_EXIST);
            }

            $fields = $request->validate([
                'name' => 'required',
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'country_id' => 'required',
                'city_id' => 'required',
            ]);

            $country = Country::find($fields['country_id']);
            if (!$country) {
                return $this->badRequest(Messages::COUNTRY_NOT_FOUND);
            }

            $city = City::find($fields['city_id']);
            if (!$city) {
                return $this->badRequest(Messages::CITY_NOT_FOUND);
            }

            if ($city->country_id != $country->id) {
                return $this->badRequest(Messages::CITY_NOT_BELONGS_TO_COUNTRY);
            }

            $chalet = Chalet::create([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'country_id' => $fields['country_id'],
                'city_id' => $fields['city_id'],
                'user_id' => $user->id,
            ]);

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function about(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'about' => 'required',
            ]);

            $chalet->about = $fields['about'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function image(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'image_id' => 'required',
            ]);

            $image = Image::find($fields['image_id']);
            if (!$image) {
                return $this->badRequest(Messages::IMAGE_NOT_FOUND);
            }
            $chalet->image_id = $fields['image_id'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function maxVisitorsNumber(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'max_number' => 'required',
            ]);

            $chalet->max_number = $fields['max_number'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function times(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'day_time' => 'required',
                'night_time' => 'required',
            ]);

            $chalet->day_time = $fields['day_time'];
            $chalet->night_time = $fields['night_time'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function facebook(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'facebook' => 'required',
            ]);

            $chalet->facebook = $fields['facebook'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function instagram(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'instagram' => 'required',
            ]);

            $chalet->instagram = $fields['instagram'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function video(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'video' => 'required',
            ]);

            $chalet->video = $fields['video'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function address(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
            ]);

            $chalet->address = $fields['address'];
            $chalet->latitude = $fields['latitude'];
            $chalet->longitude = $fields['longitude'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function downPayment(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'down_payment_percent' => 'required',
            ]);

            $chalet->down_payment_percent = $fields['down_payment_percent'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function imageId(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'user_id_image_id' => 'required',
            ]);

            $chalet->user_id_image_id = $fields['user_id_image_id'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function license(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'license' => 'required',
            ]);

            $chalet->license = $fields['license'];
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function pricing(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $chalet = Chalet::where('user_id', '=', $user->id)->first();
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $fields = $request->validate([
                'sunday_to_wednesday_day' => 'required',
                'sunday_to_wednesday_night' => 'required',
                'saturday_and_thursday_day' => 'required',
                'saturday_and_thursday_night' => 'required',
                'friday_day' => 'required',
                'friday_night' => 'required',
                'full_day_extra_price' => 'required',
            ]);

            $pricing = ChaletPricing::where('chalet_id', '=', $chalet->id)->first();
            if ($pricing) {
                $pricing->sunday_to_wednesday_day = $fields['sunday_to_wednesday_day'];
                $pricing->sunday_to_wednesday_night = $fields['sunday_to_wednesday_night'];
                $pricing->saturday_and_thursday_day = $fields['saturday_and_thursday_day'];
                $pricing->saturday_and_thursday_night = $fields['saturday_and_thursday_night'];
                $pricing->friday_day = $fields['friday_day'];
                $pricing->friday_night = $fields['friday_night'];
                $pricing->full_day_extra_price = $fields['full_day_extra_price'];
                $pricing->save();
            } else {
                $pricing = ChaletPricing::create([
                    'sunday_to_wednesday_day' => $fields['sunday_to_wednesday_day'],
                    'sunday_to_wednesday_night' => $fields['sunday_to_wednesday_night'],
                    'saturday_and_thursday_day' => $fields['saturday_and_thursday_day'],
                    'saturday_and_thursday_night' => $fields['saturday_and_thursday_night'],
                    'friday_day' => $fields['friday_day'],
                    'friday_night' => $fields['friday_night'],
                    'full_day_extra_price' => $fields['full_day_extra_price'],
                    'chalet_id' => $chalet->id,
                ]);
            }

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function readyList(Request $request)
    {
        try {

            $search = null;
            if($request->input('search')){
                $search = $request->input('search');
            }

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $data = Chalet::whereNotNull('phone')
                ->whereNotNull('about')
                ->whereNotNull('image_id')
                ->whereNotNull('day_time')
                ->whereNotNull('night_time')
                ->whereNotNull('address')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereNotNull('user_id_image_id')
                ->whereNotNull('license')
                ->where('is_approved','=',0)
                ->where('is_blocked','!=',1);

            if($search){
                $data = $data->where('name','like','%'.$search.'%');
            }

            return ChaletListItemResource::collection($data->simplePaginate());
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {

            $search = null;
            if($request->has('search')){
                $search = $request->input('search');
            }

            $isApproved = false;
            if($request->has('approved')){
                $isApproved = $request->boolean('approved');
            }

            $isBlocked = false;
            if($request->has('blocked')){
                $isBlocked = $request->boolean('blocked');
            }
            
            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $data = Chalet::where('is_approved','=',$isApproved);

            
            if($isBlocked){
                $data = $data->where('is_blocked','=',$isBlocked);
            }
            
            if($search){
                $data = $data->where('name','like','%'.$search.'%');
            }

            return ChaletListItemResource::collection($data->simplePaginate());
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    
    public function approve(Request $request)
    {
        try {

            $fields = $request->validate([
                'chalet_id' => 'required',
            ]);

            $chalet = Chalet::find($fields['chalet_id']);
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            if($chalet->phone == null || 
                $chalet->about == null || 
                $chalet->image_id == null || 
                $chalet->day_time == null || 
                $chalet->night_time == null || 
                $chalet->address == null || 
                $chalet->latitude == null || 
                $chalet->longitude == null || 
                $chalet->user_id_image_id == null || 
                $chalet->license == null
            ){
                return $this->badRequest(Messages::CHALET_NOT_READY);
            }

            $pricing = ChaletPricing::where('chalet_id','=',$chalet->id)->first();
            if(!$pricing){
                return $this->badRequest(Messages::CHALET_PRICING_NOT_FOUND);
            }

            if($chalet->is_approved){
                return $this->badRequest(Messages::CHALET_ALREADY_APPROVED);
            }

            $chalet->is_approved = true;
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    
    public function block(Request $request)
    {
        try {

            $fields = $request->validate([
                'chalet_id' => 'required',
            ]);

            $chalet = Chalet::find($fields['chalet_id']);
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $chalet->is_blocked = true;
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    
    public function unblock(Request $request)
    {
        try {

            $fields = $request->validate([
                'chalet_id' => 'required',
            ]);

            $chalet = Chalet::find($fields['chalet_id']);
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $chalet->is_blocked = false;
            $chalet->save();

            return new ChaletProfileResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
}
