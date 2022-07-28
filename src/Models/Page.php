<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Auth\Database\Administrator;

class Page extends Model
{
    protected $table = 'll_pages';

    protected $casts = [
        'published_at' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(Administrator::class, 'user_id');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function categories()
    {
        return $this->morphToMany(Categories::class, 'categorizable');
    }
}
