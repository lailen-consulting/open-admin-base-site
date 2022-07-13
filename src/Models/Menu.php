<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'll_menus';

    public function items()
    {
        return $this->hasMany(MenuItem::class);
    }
}
