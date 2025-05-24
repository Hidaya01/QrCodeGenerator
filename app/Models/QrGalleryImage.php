<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QrGalleryImage extends Model
{
    protected $fillable = [
        'gallery_id',
        'image_path'
    ];

    public function gallery()
    {
        return $this->belongsTo(QrGallery::class);
    }

    public function getImageUrlAttribute()
    {
        return asset($this->image_path);
    }
}