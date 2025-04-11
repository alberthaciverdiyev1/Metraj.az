<?php


use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'image',
        'code',
        'is_active',
    ];
}
