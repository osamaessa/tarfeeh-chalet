<?php

namespace App\Http\Controllers;

use App\Constant\Messages;
use App\Http\Resources\BookingDetailsResource;
use App\Models\Booking;
use App\Models\Chalet;
use App\Models\ChaletPricing;
use App\Traits\ErrorResponseTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isEmpty;

class BookingController extends Controller
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
                'chalet_id' => 'required',
                'date' => 'required',
                'days' => 'required',
                'is_full_day' => 'required',
                'is_night' => 'required',
            ]);

            $chalet = Chalet::find($fields['chalet_id']);
            if (!$chalet) {
                return $this->badRequest(Messages::CHALET_NOT_FOUND);
            }

            $chaletPricing = ChaletPricing::where('chalet_id', '=', $chalet->id)->first();
            if (!$chaletPricing) {
                return $this->badRequest(Messages::CHALET_PRICING_NOT_FOUND);
            }

            $price = 0;
            $chaletDay = $chalet->day_time;
            $chaletDayStart = explode(" - ", $chaletDay)[0];

            $chaletNight = $chalet->night_time;
            $chaletNightStart = explode(" - ", $chaletNight)[0];

            $startDate = now();
            $endDate = $startDate;
            if ($fields['days'] > 1) {
                $startDate = Carbon::parse($fields['date'])->setHours($chaletDayStart);
                $endDate = $endDate->copy()->addDays($fields['days']);
                $current = $startDate;

                for ($i = 1; $i <= $fields['days']; $i++) {
                    if ($current->dayName == 'Friday') {
                        $price += $chaletPricing->friday_day + $chaletPricing->full_day_extra_price;
                    } else if ($current->dayName == 'Saturday' || $current->dayName == 'Thursday') {
                        $price += $chaletPricing->saturday_and_thursday_day + $chaletPricing->full_day_extra_price;
                    } else {
                        $price += $chaletPricing->sunday_to_wednesday_day + $chaletPricing->full_day_extra_price;
                    }
                    $current = $current->copy()->addDays(1);
                }
                $endDate = $endDate->addHours(-1);
            } else {
                if ($fields['is_full_day']) {
                    $startDate = Carbon::parse($fields['date'])->setHours($chaletDayStart);
                    $endDate = $startDate;
                    $endDate = $endDate->copy()->addHours(23);
                    if ($startDate->dayName == 'Friday') {
                        $price += $chaletPricing->friday_day + $chaletPricing->full_day_extra_price;
                    } else if ($startDate->dayName == 'Saturday' || $startDate->dayName == 'Thursday') {
                        $price += $chaletPricing->saturday_and_thursday_day + $chaletPricing->full_day_extra_price;
                    } else {
                        $price += $chaletPricing->sunday_to_wednesday_day + $chaletPricing->full_day_extra_price;
                    }
                } else {
                    if ($fields['is_night']) {
                        $startDate = Carbon::parse($fields['date'])->setHours($chaletNightStart);
                        $endDate = $startDate;
                        $endDate = $endDate->copy()->addHours(11);

                        if ($startDate->dayName == 'Friday') {
                            $price += $chaletPricing->friday_night;
                        } else if ($startDate->dayName == 'Saturday' || $startDate->dayName == 'Thursday') {
                            $price += $chaletPricing->saturday_and_thursday_night;
                        } else {
                            $price += $chaletPricing->sunday_to_wednesday_night;
                        }
                    } else {
                        $startDate = Carbon::parse($fields['date'])->setHours($chaletDayStart);
                        $endDate = $startDate;
                        $endDate = $endDate->copy()->addHours(11);
                        if ($startDate->dayName == 'Friday') {
                            $price += $chaletPricing->friday_day;
                        } else if ($startDate->dayName == 'Saturday' || $startDate->dayName == 'Thursday') {
                            $price += $chaletPricing->saturday_and_thursday_day;
                        } else {
                            $price += $chaletPricing->sunday_to_wednesday_day;
                        }
                    }
                }
            }

            $conflictingBookings = Booking::where('chalet_id', $chalet->id)
                ->whereIn('status', [Booking::BOOKING_STATUS_PENDING, Booking::BOOKING_STATUS_PENDING_PAYMENT, Booking::BOOKING_STATUS_COMPLETED])
                ->where(function ($query) use ($startDate, $endDate) {
                    $query->where(function ($query) use ($startDate, $endDate) {
                        $query->where('start_date', '<', $endDate)
                            ->where('end_date', '>=', $startDate);
                    })
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_date', '<=', $endDate)
                                ->where('end_date', '>', $startDate);
                        })
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_date', '>=', $startDate)
                                ->where('end_date', '<=', $endDate);
                        });
                })->get();
            if ($conflictingBookings->count() > 0) {
                return $this->badRequest(Messages::CHALET_BOOKED_THIS_TIME);
            }

            $tax = 16;
            $taxPrice = $price * ($tax / 100);

            $taxPrice = round($taxPrice, 0, PHP_ROUND_HALF_DOWN);
            $total = $price + $taxPrice;

            $paidAmount = $total * ($chalet->down_payment_percent / 100);
            $paidAmount = round($paidAmount, 0, PHP_ROUND_HALF_DOWN);
            $booking = Booking::create([
                'chalet_id' => $fields['chalet_id'],
                'start_date' => $startDate,
                'end_date' => $endDate,
                'status' => Booking::BOOKING_STATUS_PENDING,
                'user_id' => $user->id,
                'total_price' => $total,
                'paid_amount' => $paidAmount,
                'is_review_seen' => false,
                'payment_id' => null,
                'tax' => 16.0,
            ]);

            return new BookingDetailsResource($booking);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function cancel(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $fields = $request->validate([
                'id' => 'required',
            ]);

            $booking = Booking::find($fields['id']);
            if (!$booking) {
                return $this->badRequest(Messages::BOOKING_NOT_FOUND);
            }
            if($booking->user_id != $user->id){
                return $this->forbidden();
            }

            if($booking->status != Booking::BOOKING_STATUS_PENDING){
                return $this->badRequest(Messages::BOOKING_NOT_PENDING);
            }
            $booking->status = Booking::BOOKING_STATUS_CANCELED;
            $booking->save();
            return new BookingDetailsResource($booking);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function reject(Request $request)
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
                'id' => 'required',
            ]);

            $booking = Booking::find($fields['id']);
            if (!$booking) {
                return $this->badRequest(Messages::BOOKING_NOT_FOUND);
            }

            if($booking->chalet_id != $chalet->id){
                return $this->forbidden();
            }

            if($booking->status != Booking::BOOKING_STATUS_PENDING){
                return $this->badRequest(Messages::BOOKING_NOT_PENDING);
            }
            $booking->status = Booking::BOOKING_STATUS_REJECTED;
            $booking->save();
            return new BookingDetailsResource($booking);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function approve(Request $request)
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
                'id' => 'required',
            ]);

            $booking = Booking::find($fields['id']);
            if (!$booking) {
                return $this->badRequest(Messages::BOOKING_NOT_FOUND);
            }

            if($booking->chalet_id != $chalet->id){
                return $this->forbidden();
            }

            $date = Carbon::parse($booking->start_date);
            if(now()->isAfter($date)){
                $booking->status = Booking::BOOKING_STATUS_CANCELED;
                $booking->save();
                return $this->badRequest(Messages::BOOKING_BEFORE_NOW);
            }

            if($booking->status != Booking::BOOKING_STATUS_PENDING){
                return $this->badRequest(Messages::BOOKING_NOT_PENDING);
            }
            $booking->status = Booking::BOOKING_STATUS_PENDING_PAYMENT;
            $booking->save();
            return new BookingDetailsResource($booking);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function complete(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $fields = $request->validate([
                'id' => 'required',
                'transaction_ref' => 'required',
            ]);

            $booking = Booking::find($fields['id']);
            if (!$booking) {
                return $this->badRequest(Messages::BOOKING_NOT_FOUND);
            }
            if($booking->user_id != $user->id){
                return $this->forbidden();
            }

            $date = Carbon::parse($booking->start_date);
            if(now()->isAfter($date)){
                $booking->status = Booking::BOOKING_STATUS_CANCELED;
                $booking->save();
                return $this->badRequest(Messages::BOOKING_BEFORE_NOW);
            }

            if($booking->status != Booking::BOOKING_STATUS_PENDING_PAYMENT){
                return $this->badRequest(Messages::BOOKING_STATUS_NOT_PENDING_PAYMENT);
            }

            $paytabsBaseUrl = 'https://secure-jordan.paytabs.com/payment/query';
            $profileId = '123339';
            $secretKey = 'S2J9L2WGM6-J6M62ZDLNT-ZHTKTJWJJ2';
            
            $response = Http::withHeaders([
                'authorization' => $secretKey,
            ])->post($paytabsBaseUrl, [
                'profile_id' => $profileId,
                'tran_ref' => $fields['transaction_ref'],
            ]);

            $responseData = json_decode($response->getBody(), true);
            if($responseData['payment_result']['response_status'] != 'A'){
                return $this->badRequest(Messages::BOOKING_ERROR_PAYMENT);
            }

            $chalet = Chalet::find($booking->chalet_id);
            $chalet->balance = $chalet->balance + $booking->paid_amount;

            $booking->status = Booking::BOOKING_STATUS_COMPLETED;
            $booking->save();
            return new BookingDetailsResource($booking);
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function adminList(Request $request)
    {
        try {

            $status = Booking::BOOKING_STATUS_PENDING;
            if($request->has('status')){
                $status = $request->input('status');
            }else { 
                return $this->badRequest(Messages::BOOKING_STATUS_REQUIRED);
            }

            $booking = Booking::where('status', '=', $status);
            return BookingDetailsResource::collection($booking->simplePaginate());
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function chaletList(Request $request)
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

            $status = Booking::BOOKING_STATUS_PENDING;
            if($request->has('status')){
                $status = $request->input('status');
            }else { 
                return $this->badRequest(Messages::BOOKING_STATUS_REQUIRED);
            }

            $booking = Booking::where('chalet_id', '=', $chalet->id)->where('status', '=', $status);
            return BookingDetailsResource::collection($booking->simplePaginate());
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }

    public function userList(Request $request)
    {
        try {

            $user = $request->user();

            if (!$user) {
                return $this->authenticationError();
            }

            $status = Booking::BOOKING_STATUS_PENDING;
            if($request->has('status')){
                $status = $request->input('status');
            }else { 
                return $this->badRequest(Messages::BOOKING_STATUS_REQUIRED);
            }

            $booking = Booking::where('user_id', '=', $user->id)->where('status', '=', $status);
            return BookingDetailsResource::collection($booking->simplePaginate());
        } catch (Exception $ex) {
            return $this->serverError($ex->getMessage());
        }
    }
}
