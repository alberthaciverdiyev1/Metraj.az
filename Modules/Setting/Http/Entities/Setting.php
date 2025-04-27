<?php

namespace Modules\Setting\Http\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Setting extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'settings';

    protected $fillable = [
        'name',
        'logo',
        'favicon',
        'description',
        'email',
        'phone',
        'phone_1',
        'phone_2',
        'address',
        'google_map_url',
        'whatsapp_number',
        'instagram_url',
        'facebook_url',
        'linkedin_url',
        'tiktok_url',
        'payment_username',
        'payment_password',
        'otp_login',
        'otp_password',
        'otp_sender_name',
        'is_offline',
        'about',
        'terms_and_conditions',
        'privacy_and_policy'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $attributes = [
        'is_offline' => false,
    ];
}

