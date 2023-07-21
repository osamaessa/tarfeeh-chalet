<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'phone_code',
        'name_en',
        'name_ar',
        'currency',
        'image_id',
        'iso',
    ];
}
