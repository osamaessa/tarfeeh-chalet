<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    use ErrorResponseTrait;
    public function upload(Request $request)
    {
        try {
            if (!$request->validate(['image' => 'required|mimes:jpg,jpeg,png'])) {
                return $this->badRequest("Image wrong extention");
            }

            $fileName = 'IMG_' . (microtime(true) * 10000) . '.' . $request->file('image')->extension();
            $request->image->move(public_path('images'), $fileName);
            $url = '/images/' . $fileName;
    
            return new ImageResource(Image::create(array(
                "url" => $url,
            )));
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
        
    }
}
