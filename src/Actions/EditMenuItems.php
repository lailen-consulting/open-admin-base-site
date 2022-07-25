<?php

namespace Lailen\OpenAdmin\Site\Actions;

use OpenAdmin\Admin\Actions\RowAction;

class EditMenuItems extends RowAction
{
    public $name = 'edit-menu-items';

    public $icon = 'icon-edit';

    public function href()
    {
        return admin_url('ll_menus/' . $this->getKey() . '/items');
    }
}
