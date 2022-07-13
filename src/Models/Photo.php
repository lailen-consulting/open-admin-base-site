<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'll_gallery_photos';

    protected $fillable = [
        'album_id',
        'caption',
        'path',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
