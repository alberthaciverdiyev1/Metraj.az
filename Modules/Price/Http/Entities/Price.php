<?php

namespace Modules\Price\Http\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Price extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'amount',
        'currency',
        'vehicle_id',
    ];
}
