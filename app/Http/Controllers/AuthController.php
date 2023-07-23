<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\AuthResource;
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
                'country_id' => 'required',
            ]);

            $user = User::create([
                'name' => $fields['name'],
                'phone' => $fields['phone'],
                'password' => bcrypt($fields['password']),
                'country_id' => $fields['country_id'],
                'verified_at' => now(),
                'type' => User::TYPE_ADMIN,
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
