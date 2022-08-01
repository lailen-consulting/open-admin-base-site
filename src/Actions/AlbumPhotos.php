<?php

namespace Lailen\OpenAdmin\Site\Actions;

use OpenAdmin\Admin\Actions\RowAction;

class AlbumPhotos extends RowAction
{
    public $name = 'view-photos';

    public $icon = 'icon-images';

    public function href()
    {
        return admin_url('ll_albums/' . $this->getKey() . '/photos');
    }
}
