<?php

namespace Lailen\OpenAdmin\Site\Actions;

use OpenAdmin\Admin\Actions\RowAction;

class EditMenuItems extends RowAction
{
    public $name = 'edit-menu-items';

    public $icon = 'icon-edit';

    public function href()
    {
        // admin_route emawni hman tur
        return '/admin/menus/' . $this->getKey() . '/items';
    }
}
