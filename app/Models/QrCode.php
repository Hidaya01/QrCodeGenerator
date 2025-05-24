<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class QrCode extends Model
{
    use Translatable;

    protected $translatable = [];

    protected $fillable = [
        'type',
        'content',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gallery()
    {
        return $this->hasOne(QrGallery::class);
    }

    public function socialLink()
    {
        return $this->hasOne(QrSocialLink::class);
    }

    public function vcard()
    {
        return $this->hasOne(QrVcard::class);
    }

    public function getFullUrlAttribute()
    {
        if ($this->type === 'website') {
            return $this->content;
        }

        if ($this->type === 'gallery') {
            return route('gallery.view', ['id' => $this->id]);
        }

        if ($this->type === 'social') {
            return route('qr.social.landing', ['id' => $this->id]);
        }

        if ($this->type === 'vcard') {
            return $this->vcard->vcard_url ?? '#';
        }

        return $this->content;
    }
    public function file()
    {
        return $this->hasOne(QrFile::class);
    }
}