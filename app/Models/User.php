<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'verified_at',
        'code',
        'type',
        'token',
        'refresh_token',
        'token_expire_at',
        'fcm',
        'address',
        'latitude',
        'longitude',
        'is_blocked',
        'reports_count',
        'image_id',
        'city_id',
        'country_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
