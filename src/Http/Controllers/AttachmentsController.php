<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Lailen\OpenAdmin\Site\Helpers;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use Lailen\OpenAdmin\Site\Models\Attachment;

class AttachmentsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Attachments';

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Attachment());

        Helpers::setupAttachmentForm($form);

        return $form;
    }
}
