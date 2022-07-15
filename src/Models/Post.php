<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenAdmin\Admin\Auth\Database\Administrator;
use \OpenAdmin\Admin\Traits\Resizable;

class Post extends Model
{
    use SoftDeletes, Resizable;

    protected $table = 'll_posts';

    protected $casts = [
        'published_at' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(Administrator::class, 'user_id');
    }

    public function categories()
    {
        return $this->belongsToMany(PostCategory::class, 'll_posts_categories');
    }

    public function tags()
    {
        return $this->belongsToMany(PostTag::class, 'll_posts_tags');
    }
}
