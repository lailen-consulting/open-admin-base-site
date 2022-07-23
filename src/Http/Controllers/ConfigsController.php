<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Models\Config;

class ConfigsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Setting Configurations';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Config());

        $grid->column('id', __('Id'));
        $grid->column('title', __('Title'));
        $grid->column('key', __('Key'));
        $grid->column('value', __('Value'));
        $grid->column('type', __('Type'));

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Config::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('title', __('Title'));
        $show->field('key', __('Key'));
        $show->field('value', __('Value'));
        $show->field('type', __('Type'));
        $show->field('options', __('options'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Config());

        $form->text('title', __('Title'))->required();
        $form->text('key', __('Key'));
        // $form->text('value', __('Value')); value hi settings UI atanga thun tur
        $form->select('type', __("Type"))->options([
            'text' => 'Text',
            'textarea' => 'Textarea',
            'select' => 'Select', // options ah value => label
            'radio' => 'Radio', // options ah value => label
            'checkbox' => 'Checkbox', // options ah value => label
            'single-page' => 'Single Page',
            // 'post-list' => 'Post List', // options ah order, category etc
            'single-menu' => 'Single Menu',
            'single-album' => 'Single Album',
            'file' => 'File',
            'post-categories' => 'Post Categories',
            // 'files' => 'Files', // buaithlak
        ]);
        $form->textarea('options', __('Options'));

        return $form;
    }
}
