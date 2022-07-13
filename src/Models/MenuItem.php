<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Traits\ModelTree;

class MenuItem extends Model
{
    protected $table = 'll_menu_items';

    protected $fillable = ['title', 'link', 'menu_id', 'order'];

    use ModelTree {
        ModelTree::boot as treeBoot;
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class);
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function boot()
    {
        static::treeBoot();

        static::deleting(function ($model) {
            $model->roles()->detach();
        });
    }
}
