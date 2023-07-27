<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'chalet_id',
        'user_id',
        'status',
        'start_date',
        'end_date',
        'is_review_seen',
        'payment_id',
        'paid_amount',
        'tax',
        'total_price',
    ];

    const BOOKING_STATUS_PENDING = "BOOKING_STATUS_PENDING";
    const BOOKING_STATUS_REJECTED = "BOOKING_STATUS_REJECTED";
    const BOOKING_STATUS_CANCELED = "BOOKING_STATUS_CANCELED";
    const BOOKING_STATUS_PENDING_PAYMENT = "BOOKING_STATUS_PENDING_PAYMENT";
    const BOOKING_STATUS_COMPLETED = "BOOKING_STATUS_COMPLETED";
}
