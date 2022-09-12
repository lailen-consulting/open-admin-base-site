<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    use HasFactory;

    protected $table = 'll_attachments';

    protected $fillable = [
        'location',
        'title',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
}
