<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chalet extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'phone',
        'about',
        'user_id',
        'image_id',
        'reviews_count',
        'review',
        'views',
        'max_number',
        'balance',
        'day_time',
        'night_time',
        'facebook',
        'instagram',
        'video',
        'address',
        'latitude',
        'longitude',
        'is_blocked',
        'is_special',
        'is_approved',
        'down_payment_percent',
        'city_id',
        'country_id',
        'user_id_image_id',
        'license',
    ];
}
