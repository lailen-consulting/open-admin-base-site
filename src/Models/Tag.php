<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tag extends Model
{
    use SoftDeletes;

    protected $table = 'll_tags';

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'taggable');
    }

    public function pages()
    {
        return $this->morphedByMany(Page::class, 'taggable');
    }

    public function photos()
    {
        return $this->morphedByMany(Photo::class, 'taggable');
    }

    public function albums()
    {
        return $this->morphedByMany(Album::class, 'taggable');
    }
}
