<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Carbon\Carbon;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Models\Tag;

class TagsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Tags';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Tag());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $grid->column('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });

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
        $show = new Show(Tag::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Tag());

        $form->text('name', __('Name'))->required();

        return $form;
    }
}
