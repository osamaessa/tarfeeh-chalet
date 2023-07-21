<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChaletImage extends Model
{
    use HasFactory;
    protected $fillable = [
        'chalet_id',
        'image_id',
    ];
}
