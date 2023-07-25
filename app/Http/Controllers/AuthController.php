<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Models\Country;
use App\Models\User;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ErrorResponseTrait;

    public function registerAdmin(Request $request) {
        try {

            $code = "745484";
            $fields = $request->validate([
                'name' => 'required',
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'password' => 'required',
                // 'country_id' => 'required',
            ]);

            $user = User::create([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'password' => bcrypt($fields['password']),
                // 'country_id' => $fields['country_id'],
                'verified_at' => now(),
                'type' => User::TYPE_ADMIN,
                'code' => $code,
            ]);

            return $user;
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    public function registerSubAdmin(Request $request) {
        try {

            $code = "745484";
            $fields = $request->validate([
                'name' => 'required',
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'password' => 'required',
                'country_id' => 'required',
            ]);

            $user = User::create([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'password' => bcrypt($fields['password']),
                'country_id' => $fields['country_id'],
                'verified_at' => now(),
                'type' => User::TYPE_SUBADMIN,
                'code' => $code,
            ]);

            return $user;
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function register(Request $request) {
        try {

            $code = random_int(100000, 999999);
            $fields = $request->validate([
                'name' => 'required',
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'password' => 'required',
                'country_id' => 'required',
            ]);

            $user = User::where('phone','=',$fields['phone'])->first();
            if ($user) {
                return $this->badRequest(Messages::USER_ALREADY_REGISTERED);
            }

            $country = Country::find($fields['country_id']);
            if(!$country){
                return $this->badRequest(Messages::COUNTRY_NOT_FOUND);
            }
            $user = User::create([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'password' => bcrypt($fields['password']),
                'country_id' => $fields['country_id'],
                'type' => User::TYPE_USER,
                'code' => $code,
            ]);

            return response(array(
                'message' => Messages::USER_REGISTERED_SUCCESS
            ));
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function registerChaletUser(Request $request) {
        try {

            $code = random_int(100000, 999999);
            $fields = $request->validate([
                'name' => 'required',
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'password' => 'required',
                'country_id' => 'required',
            ]);

            $user = User::where('phone','=',$fields['phone'])->first();
            if ($user) {
                return $this->badRequest(Messages::USER_ALREADY_REGISTERED);
            }
            $user = User::create([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'password' => bcrypt($fields['password']),
                'country_id' => $fields['country_id'],
                'type' => User::TYPE_CHALET,
                'code' => $code,
            ]);

            return response(array(
                'message' => Messages::USER_REGISTERED_SUCCESS
            ));
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    
    public function login(Request $request) {
        try {

            $fields = $request->validate([
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'password' => 'required',
            ]);

            $user = User::where('phone',$fields['phone'])->first();
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }

            if(!$user->verified_at){
                return $this->badRequest(Messages::USER_NOT_VERIFIED);
            }

            if(!Hash::check($request->password, $user->password)){
                return $this->badRequest(Messages::INVALID_CRED);
            }
            foreach ($user->tokens as $token) {
                $token->delete();
            }

            return response([
                'token' => $user->createToken('user_token')->plainTextToken,
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
            ]);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    
    public function verify(Request $request) {
        try {

            $fields = $request->validate([
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'code' => 'required',
            ]);

            $user = User::where('phone',$fields['phone'])->first();
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }

            if($fields['code'] != $user->code){
                return $this->badRequest(Messages::VERIFY_CODE_WRONG);
            }

            foreach ($user->tokens as $token) {
                $token->delete();
            }
            $user->verified_at = now();
            $user->save();
            return response([
                'token' => $user->createToken('user_token')->plainTextToken,
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
            ]);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    
    public function forgetPassword(Request $request) {
        try {

            $fields = $request->validate([
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
            ]);

            $user = User::where('phone',$fields['phone'])->first();
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }

            if(!$user->verified_at){
                return $this->badRequest(Messages::USER_NOT_VERIFIED);
            }

            foreach ($user->tokens as $token) {
                $token->delete();
            }
            $code = random_int(100000, 999999);
            $user->code = $code;
            $user->save();
            return response(array(
                'message' => Messages::CODE_SEND_SUCCESS
            ));
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function forgetPasswordVerify(Request $request) {
        try {

            $fields = $request->validate([
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'code' => 'required',
            ]);

            $user = User::where('phone',$fields['phone'])->first();
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }

            if($fields['code'] != $user->code){
                return $this->badRequest(Messages::VERIFY_CODE_WRONG);
            }
            return response(array(
                'message' => Messages::USER_VERIFIED_SUCCESS
            ));
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function resetPassword(Request $request) {
        try {

            $fields = $request->validate([
                'phone' => 'required|regex:/^\+9627[789]\d{7}$/',
                'password' => 'required',
                'code' => 'required',
            ]);

            $user = User::where('phone',$fields['phone'])->first();
            if(!$user){
                return $this->badRequest(Messages::USER_NOT_FOUND);
            }

            if($fields['code'] != $user->code){
                return $this->badRequest(Messages::VERIFY_CODE_WRONG);
            }

            foreach ($user->tokens as $token) {
                $token->delete();
            }
            $user->password = bcrypt($fields['password']);
            $user->save();
            return response(array(
                'message' => Messages::PASSWORD_RESET_SUCCESS
            ));
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function refreshToken(Request $request) {
        try {

            $user = $request->user();

            if(!$user){
                return $this->authenticationError();
            }
            foreach ($user->tokens as $token) {
                $token->delete();
            }

            return response([
                'token' => $user->createToken('user_token')->plainTextToken,
                'id' => $user->id,
                'name' => $user->name,
                'phone' => $user->phone,
            ]);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    
}
