<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class QrSocialPlatform extends Model
{
    use Translatable;

    protected $translatable = ['platform_name', 'text'];

    protected $fillable = [
        'social_link_id',
        'platform_name',
        'url',
        'text'
    ];

    public function socialLink()
    {
        return $this->belongsTo(QrSocialLink::class);
    }

    public function getPlatformIconAttribute()
    {
        $platform = strtolower($this->platform_name);
        
        $icons = [
            'facebook' => 'voyager-facebook',
            'twitter' => 'voyager-twitter',
            'instagram' => 'voyager-instagram',
            'linkedin' => 'voyager-linkedin',
            'youtube' => 'voyager-youtube',
            'pinterest' => 'voyager-pinterest',
            'tiktok' => 'voyager-video',
        ];

        return $icons[$platform] ?? 'voyager-share-alt';
    }
}