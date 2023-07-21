<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChaletPricing extends Model
{
    use HasFactory;
    protected $fillable = [
        'chalet_id',
        'sunday_to_wednesday_day',
        'sunday_to_wednesday_night',
        'saturday_and_thursday_day',
        'saturday_and_thursday_night',
        'friday_day',
        'friday_night',
        'full_day_extra_price',
    ];
}
