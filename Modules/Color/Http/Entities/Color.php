<?php


namespace Modules\Color\Http\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Color\Database\Factories\ColorFactory;

class Color extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'code',
        'is_active',
    ];

    public static function newFactory(): ColorFactory
    {
        return ColorFactory::new();
    }
}
