<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Auth\Database\Administrator;

class Album extends Model
{
    protected $table = 'll_gallery_albums';

    protected $casts = [
        'time' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(Administrator::class, 'user_id');
    }

    public function categories()
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }
}
