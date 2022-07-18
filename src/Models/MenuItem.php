<?php

namespace Lailen\OpenAdmin\Site\Models;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Traits\ModelTree;

class MenuItem extends Model
{
    protected $table = 'll_menu_items';

    protected $fillable = ['title', 'link', 'menu_id', 'order', 'icon'];

    use ModelTree {
        ModelTree::boot as treeBoot;
    }
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
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

    protected function updateItemOrder ($item, $key, $parentId = null) {
        if (isset($item->children)) {
            MenuItem::where('id', $item->id)->update(['order' => ($key + 1), 'parent_id' => $parentId]);
            foreach($item->children as $childKey => $child) {
                $this->updateItemOrder($child, $childKey + 1, $item->id);
                return;
            }
        } else {
            MenuItem::where('id', $item->id)->update(['order' => ($key + 1), 'parent_id' => $parentId]);
            return;
        }
    }
}
