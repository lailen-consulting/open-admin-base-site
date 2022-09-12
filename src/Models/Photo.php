<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $table = 'll_gallery_photos';

    protected $fillable = [
        'album_id',
        'caption',
        'image',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }
}
