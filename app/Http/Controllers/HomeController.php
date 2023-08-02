<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\ChaletDetailsResource;
use App\Http\Resources\ChaletListItemResource;
use App\Models\Chalet;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    use ErrorResponseTrait;

    public function home(Request $request)
    {
        try {

            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $specialChalets = null;
            $newChalets = null;
            $mostReviewedChalets = null;
            $nearbyChalets = null;

            $specialChalets = Chalet::where('is_approved', true)
                ->where('is_blocked', '!=', true)
                ->where('is_special', '=', true)
                ->take(7) // Limit the result to 7 nearest places
                ->get();

            $mostReviewedChalets = Chalet::where('is_approved', true)
                ->where('is_blocked', '!=', true)
                ->orderBy('review', 'desc')
                ->take(7) // Limit the result to 7 nearest places
                ->get();

            $newChalets = Chalet::where('is_approved', true)
                ->where('is_blocked', '!=', true)
                ->orderBy('created_at', 'desc')
                ->take(7) // Limit the result to 7 nearest places
                ->get()
                ->shuffle();
            if ($latitude && $longitude) {
                $nearbyChalets = Chalet::where('is_approved', true)
                    ->where('is_blocked', '!=', true)
                    ->select(DB::raw('*, 
                        (6371 * acos(cos(radians(' . $latitude . ')) 
                        * cos(radians(latitude)) 
                        * cos(radians(longitude) - radians(' . $longitude . ')) 
                        + sin(radians(' . $latitude . ')) 
                        * sin(radians(latitude)))
                        ) AS distance'))
                    ->having('distance', '<', 200)
                    ->orderBy('distance', 'asc')
                    ->take(7) // Limit the result to 7 nearest places
                    ->get();
            }

            return response([
                'special' => ChaletListItemResource::collection($specialChalets),
                'new' => ChaletListItemResource::collection($newChalets),
                'most_reviewed' => ChaletListItemResource::collection($mostReviewedChalets),
                'nearby' => ChaletListItemResource::collection($nearbyChalets),
            ]);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function search(Request $request)
    {
        try {

            $search = null;
            if ($request->has('search')) {
                $search = $request->input('search');
            }
            $cityId = null;
            if ($request->has('city_id')) {
                $cityId = $request->input('city_id');
            }
            $size = 15;
            if ($request->has('size')) {
                $size = $request->input('size');
            }

            $data = Chalet::where('is_approved', true)
                ->where('is_blocked', '!=', true);
            if ($cityId) {
                $data = $data->where('city_id', '=', $cityId);
            }
            if ($search) {
                $data = $data->where('name', 'LIKE', '%'.$search.'%');
            }

            return ChaletListItemResource::collection($data->simplePaginate($size));
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
    public function chaletDetails(Request $request)
    {
        try {

            $id = null;
            if ($request->has('id')) {
                $id = $request->input('id');
            }else {
                return $this->badRequest(Messages::CHALET_ID_REQUIRED);
            }
            

            $chalet = Chalet::find($id);
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }
            
            return new ChaletDetailsResource($chalet);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
}
