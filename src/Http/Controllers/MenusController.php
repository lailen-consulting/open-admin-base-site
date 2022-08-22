<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Actions\EditMenuItems;
use Lailen\OpenAdmin\Site\Models\Menu;
use Lailen\OpenAdmin\Site\Models\MenuItem;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Grid;

class MenusController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Menus';

    public function __construct()
    {
        $this->hook("alterForm", function ($scope, $form) {
            $form->saving(function (Form $form){
                $model = $form->model();
                if (!$model->slug) {
                    $model->slug = Str::slug($form->input('name'));
                }
            });
            return $form;
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Menu());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('slug', __('Slug'));
        $grid->column('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $grid->column('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $grid->actions(function ($actions) {
            $actions->add(new EditMenuItems());
        });

        return $grid;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Menu());

        $form->text('name', __('Name'));
        // $form->text('slug', __('Slug'));

        return $form;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Menu::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('slug', __('Slug'));
        $show->field('created_at', __('Created at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('updated_at', __('Updated at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });
        $show->field('deleted_at', __('Deleted at'))->display(function($time) {
            return Carbon::create($time)->format('dS M, Y h:i a');
        });

        return $show;
    }

    /**
     * Help message for icon field.
     *
     * @return string
     */
    protected function iconHelp()
    {
        return 'For more icons please see <a href="http://fontawesome.io/icons/" target="_blank">http://fontawesome.io/icons/</a>';
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
