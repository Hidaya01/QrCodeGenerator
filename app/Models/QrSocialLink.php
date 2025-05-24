<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class QrSocialLink extends Model
{
    use Translatable;

    protected $translatable = [];

    protected $fillable = [
        'qr_code_id',
        'website_url'
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }

    public function platforms()
    {
        return $this->hasMany(QrSocialPlatform::class, 'social_link_id');
    }

    public function getLandingUrlAttribute()
    {
        return route('qr.social.landing', ['id' => $this->qr_code_id]);
    }
}