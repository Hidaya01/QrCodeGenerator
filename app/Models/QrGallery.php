<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class QrGallery extends Model
{
    use Translatable;

    protected $translatable = ['title', 'button_text'];

    protected $fillable = [
        'qr_code_id',
        'title',
        'button_url',
        'button_text',
        'grid_view'
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }

    public function images()
    {
        return $this->hasMany(QrGalleryImage::class, 'gallery_id');
    }

    public function getGalleryUrlAttribute()
    {
        return route('gallery.view', ['id' => $this->qr_code_id]);
    }
}