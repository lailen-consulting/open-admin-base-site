<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Lailen\OpenAdmin\Site\Models\MenuItem;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Tree;
use OpenAdmin\Admin\Widgets\Box;

class MenuItemsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Menu Items';

    public function index(Content $content)
    {
        $grid = $this->grid();
        if ($this->hasHooks('alterGrid')) {
            $grid = $this->callHooks('alterGrid', $grid);
        }

        // return $content
        //     ->title($this->title())
        //     ->description($this->description['index'] ?? trans('admin.list'))
        //     ->body($grid);
        return $content
            ->title('Menu')
            ->description('Edit')
            ->row(function (Row $row) {
                $row->column(6, $this->grid());
                $row->column(6, $this->treeView()->render());
            });
    }

    public function create(Content $content)
    {
        $id = request()->route('menu');

        return $content
            ->title('Menu')
            ->description('Edit')
            ->row(function (Row $row) use ($id) {
                $row->column(6, function (Column $column) use ($id) {
                    $form = $this->form();
                    $form->action(admin_url('menus/' . $id . '/items'));
                    $column->append((new Box('New Item', $form))->style('success'));
                });

                $row->column(6, $this->treeView()->render());
            });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MenuItem());
        $grid->model()->where('menu_id', request()->route('menu'));

        $grid->column('id', __('Id'));
        $grid->column('order', __('Order'));
        $grid->column('menu_id', __('menu_id'));
        $grid->column('title', __('Title'));
        $grid->column('link', __('Link'));
        $grid->column('icon', __('Icon'));
        $grid->column('image', __('Image'))->image('/storage/admin/', 200, 200);


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
        $show = new Show(MenuItem::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('image', __('Image'))->image('/storage/admin/', 200, 200);
        $show->field('caption', __('caption'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $menuId = request()->route('menu');
        $form = new Form(new MenuItem());

        $form->select('parent_id', trans('admin.parent_id'))->options(MenuItem::selectOptions());
        $form->text('title', 'Title')->rules('required');
        $form->text('link', 'Link')->rules('required');
        $form->hidden('menu_id')->value($menuId);
        $form->textarea('description', 'Description');
        $form->image('image', 'Image');
        $form->text('icon', 'Icon');

        return $form;
    }

    protected function treeView()
    {
        $tree = new Tree(new MenuItem());
        $tree->query(function($q) {
            return $q->where('menu_id', request()->route('menu'));
        });

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<strong>{$branch['title']}</strong>";

            if (!isset($branch['children'])) {
                $link = $branch['link'];
                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$link\" class=\"dd-nodrag\">$link</a>";
            }

            return $payload;
        });

        return $tree;
    }
}
