<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\FeatureResource;
use App\Models\Chalet;
use App\Models\Features;
use App\Models\Image;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;

class FeatureController extends Controller
{
    use ErrorResponseTrait;
    
    public function add(Request $request)
    {
        try {

            $fields = $request->validate([
                'name_en' => 'required',
                'name_ar' => 'required',
                'image_id' => 'required',
            ]);

            $image = Image::find($fields['image_id']);
            if (!$image) {
                return $this->badRequest(Messages::IMAGE_NOT_FOUND);
            }

            $feature = Features::create([
                'name_en' => $fields['name_en'],
                'name_ar' => $fields['name_ar'],
                'image_id' => $fields['image_id'],
            ]);

            return new FeatureResource($feature);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function update(Request $request)
    {
        try {

            $fields = $request->validate([
                'id' => 'required',
                'name_en' => 'required',
                'name_ar' => 'required',
                'image_id' => 'required',
            ]);

            $image = Image::find($fields['image_id']);
            if (!$image) {
                return $this->badRequest(Messages::IMAGE_NOT_FOUND);
            }

            $feature = Features::find($fields['id']);
            if(!$feature){
                return $this->badRequest(Messages::FEATURE_NOT_FOUND);
            }
            $feature->name_ar = $fields['name_ar'];
            $feature->name_en = $fields['name_en'];
            $feature->image_id = $fields['image_id'];
            $feature->save();

            return new FeatureResource($feature);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {

            $fields = $request->validate([
                'id' => 'required',
            ]);

            $feature = Features::find($fields['id']);
            if(!$feature){
                return $this->badRequest(Messages::FEATURE_NOT_FOUND);
            }
            
            $feature->delete();

            return new FeatureResource($feature);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {

            $data = Features::all();

            return FeatureResource::collection($data);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
}
