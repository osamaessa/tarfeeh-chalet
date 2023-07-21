<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChaletFeatures extends Model
{
    use HasFactory;
    protected $fillable = [
        'chalet_id',
        'name_en',
        'name_ar',
    ];
}
