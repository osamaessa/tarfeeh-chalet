<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\UserMiniResource;
use App\Http\Resources\UserProfileResource;
use App\Models\City;
use App\Models\Country;
use App\Models\Image;
use App\Models\User;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{
    use ErrorResponseTrait;

    public function users(Request $request)
    {
        try {

            $search = null;
            if($request->has('search')){
                $search = $request->input('search');
            }

            $type = User::TYPE_USER;
            if($request->has('type')){
                $type = $request->input('type');
            }

            $isBlocked = false;
            if($request->has('blocked')){
                $isBlocked = $request->boolean('blocked');
            }
            
            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $data = User::where('type','=',$type);

            
            if($isBlocked){
                $data = $data->where('is_blocked','=',$isBlocked);
            }
            
            if($search){
                $data = $data->where('name','like','%'.$search.'%');
            }

            return UserMiniResource::collection($data->simplePaginate());
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function userDetails(Request $request)
    {
        try {

            $userId = null;
            if($request->has('id')){
                $userId = $request->input('id');
            }else{
                return $this->badRequest(Messages::USER_ID_REQUIRED);
            }

            $user = User::find($userId);
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function profile(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function updateName(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $fields = $request->validate([
                'name' => 'required',
            ]);

            $user->name = $fields['name'];
            $user->save();
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function updateFcm(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $fields = $request->validate([
                'fcm' => 'required',
            ]);

            $user->fcm = $fields['fcm'];
            $user->save();
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function updatePassword(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $fields = $request->validate([
                'old' => 'required',
                'new' => 'required',
            ]);

            if(!Hash::check($fields['old'], $user->password)){
                return $this->badRequest(Messages::PASSWORD_WRONG);
            }

            $user->password = bcrypt($fields['new']);
            $user->save();
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function updateAddress(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $fields = $request->validate([
                'country_id' => 'required',
                'city_id' => 'required',
                'address' => 'required',
                'latitude' => 'required',
                'longitude' => 'required',
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

            $user->country_id = $fields['country_id'];
            $user->city_id = $fields['city_id'];
            $user->address = $fields['address'];
            $user->latitude = $fields['latitude'];
            $user->longitude = $fields['longitude'];
            $user->save();
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function updateImage(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $fields = $request->validate([
                'image_id' => 'required',
            ]);

            $image = Image::find($fields['image_id']);
            if (!$image) {
                return $this->badRequest(Messages::IMAGE_NOT_FOUND);
            }

            $user->image_id = $fields['image_id'];
            $user->save();
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function block(Request $request)
    {
        try {

            $fields = $request->validate([
                'id' => 'required',
            ]);

            $user = User::find($fields['id']);
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }

            $user->is_blocked = true;
            $user->save();
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function unblock(Request $request)
    {
        try {

            $fields = $request->validate([
                'id' => 'required',
            ]);

            $user = User::find($fields['id']);
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }
            
            $user->reports_count = 0;
            $user->is_blocked = false;
            $user->save();
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function increaseReportCount(Request $request)
    {
        try {

            $fields = $request->validate([
                'id' => 'required',
            ]);

            $user = User::find($fields['id']);
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }
            
            $user->reports_count = $user->reports_count + 1;
            if($user->reports_count == 3){
                $user->is_blocked = true;
            }
            $user->save();
            return new UserProfileResource($user);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
}
