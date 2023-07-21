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
}
