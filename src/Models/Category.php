<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenAdmin\Admin\Traits\Resizable;

class Category extends Model
{
    use SoftDeletes, Resizable;

    protected $table = 'll_categories';

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'categorizable');
    }

    public function videos()
    {
        return $this->morphedByMany(Video::class, 'categorizable');
    }

    public function pages()
    {
        return $this->morphedByMany(Page::class, 'categorizable');
    }

    public function photos()
    {
        return $this->morphedByMany(Photo::class, 'categorizable');
    }

    public function albums()
    {
        return $this->morphedByMany(Album::class, 'categorizable');
    }
}
