<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class QrVcard extends Model
{
    use Translatable;

    protected $translatable = [
        'first_name',
        'last_name',
        'company',
        'job_title',
        'address',
        'summary'
    ];

    protected $fillable = [
        'qr_code_id',
        'first_name',
        'last_name',
        'mobile',
        'phone',
        'fax',
        'email',
        'company',
        'job_title',
        'address',
        'website',
        'summary',
        'image_path'
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }

    public function getVcardUrlAttribute()
    {
        return route('vcard.download', ['id' => $this->qr_code_id]);
    }

    public function getFullNameAttribute()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset($this->image_path) : null;
    }
}