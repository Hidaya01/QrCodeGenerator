<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class QrFile extends Model
{
    use Translatable;

    protected $fillable = [
        'qr_code_id',
        'original_name',
        'storage_path',
        'mime_type',
        'size',
        'type'
    ];

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }

    public function getPublicUrlAttribute()
    {
        return asset('storage/uploads/' . basename($this->storage_path));
    }

    public function getDownloadUrlAttribute()
    {
        return route('file.download', ['id' => $this->id]);
    }
}