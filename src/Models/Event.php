<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use Lailen\OpenAdmin\Site\Traits\HasCategoriesAndTags;

class Event extends Model
{
    use HasCategoriesAndTags;

    protected $table = 'll_events';
}
