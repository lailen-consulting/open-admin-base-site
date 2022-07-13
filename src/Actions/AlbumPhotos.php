<?php

namespace Lailen\OpenAdmin\Site\Actions;

use OpenAdmin\Admin\Actions\RowAction;

class AlbumPhotos extends RowAction
{
    public $name = 'view-photos';

    public $icon = 'icon-images';

    public function href()
    {
        // admin_route emawni hman tur
        return admin_url('albums/' . $this->getKey() . '/photos');
    }
}
