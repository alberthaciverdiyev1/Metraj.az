<?php

namespace App\Modules\PropertyRequest\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyRequestImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_request_id',
        'image_path',
        'sort_order',
        'is_main',
    ];

    protected $casts = [
        'is_main' => 'boolean',
        'sort_order' => 'integer',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(PropertyRequest::class, 'property_request_id');
    }

    public function getUrlAttribute(): string
    {
        if (empty($this->image_path)) {
            return asset('images/placeholder.jpg');
        }

        if (str_starts_with($this->image_path, 'http://') || str_starts_with($this->image_path, 'https://')) {
            return $this->image_path;
        }

        if (str_starts_with($this->image_path, 'storage/')) {
            return asset($this->image_path);
        }

        return Storage::disk('public')->url($this->image_path);
    }
}
