<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OpenAdmin\Admin\Auth\Database\Administrator;

class Post extends Model
{
    use SoftDeletes;

    protected $table = 'll_posts';

    protected $casts = [
        'published_at' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(Administrator::class, 'user_id');
    }
}
