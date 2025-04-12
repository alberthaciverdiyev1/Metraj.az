<?php

namespace Modules\Price\Http\Entities;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'amount',
        'vehicle_id',
    ];
}
