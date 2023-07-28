<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\ReviewResource;
use App\Models\Booking;
use App\Models\Chalet;
use App\Models\Review;
use App\Traits\ErrorResponseTrait;
use Exception;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    use ErrorResponseTrait;

    public function add(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }
            $fields = $request->validate([
                'text' => 'required',
                'stars' => 'required',
                'booking_id' => 'required',
            ]);

            $booking = Booking::find($fields['booking_id']);
            if (!$booking) {
                return $this->badRequest(Messages::BOOKING_NOT_FOUND);
            }
            if ($booking->user_id != $user->id) {
                return $this->forbidden();
            }

            $review = Review::where('user_id', '=', $user->id)->where('booking_id', '=', $booking->id)->first();
            if ($review) {
                return $this->badRequest(Messages::REVIEW_ALREADY_ADDED);
            }

            $review = Review::create([
                'user_id' => $user->id,
                'booking_id' => $fields['booking_id'],
                'text' => $fields['text'],
                'stars' => $fields['stars'],
                'chalet_id' => $booking->chalet_id,
            ]);

            $average = Review::where('chalet_id', '=', $booking->chalet_id)->avg('stars');
            $count = Review::where('chalet_id', '=', $booking->chalet_id)->count();

            $chalet = Chalet::find($booking->chalet_id);
            $chalet->review = $average;
            $chalet->reviews_count = $count;
            $chalet->save();
            return new ReviewResource($review);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function delete(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }
            $fields = $request->validate([
                'id' => 'required',
            ]);

            $review = Review::find($fields['id']);
            if (!$review) {
                return $this->badRequest(Messages::REVIEW_NOT_FOUND);
            }

            if ($review->user_id != $user->id) {
                return $this->forbidden();
            }

            $booking = Booking::find($review->booking_id);

            $review->delete();

            $chalet = Chalet::find($booking->chalet_id);
            $count = Review::where('chalet_id', '=', $booking->chalet_id)->count();
            if ($count > 0) {
                $average = Review::where('chalet_id', '=', $booking->chalet_id)->avg('stars');

                $chalet->review = $average;
                $chalet->reviews_count = $count;
                $chalet->save();
            } else {
                $average = Review::where('chalet_id', '=', $booking->chalet_id)->avg('stars');

                $chalet->review = 0;
                $chalet->reviews_count = 0;
                $chalet->save();
            }

            return new ReviewResource($review);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function list(Request $request)
    {
        try {

            $chaletId = 0;
            if ($request->has('chalet_id')) {
                $chaletId = $request->input('chalet_id');
            } else {
                return $this->badRequest(Messages::CHALET_ID_REQUIRED);
            }

            $chalet = Chalet::find($chaletId);
            if(!$chalet){
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }
            $review = Review::where('chalet_id','=',$chaletId);
            if (!$review) {
                return $this->badRequest(Messages::REVIEW_NOT_FOUND);
            }
            return ReviewResource::collection($review->simplePaginate());
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
}
