<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Actions\EditMenuItems;
use Lailen\OpenAdmin\Site\Models\Menu;
use Lailen\OpenAdmin\Site\Models\MenuItem;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Tree;
use OpenAdmin\Admin\Widgets\Box;

class MenusController extends AdminController
{
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
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
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
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

        return $show;
    }

    public function editItems($id, Content $content)
    {
        return $content
            ->title('Menu')
            ->description('Edit')
            ->row(function (Row $row) use ($id) {
                $row->column(6, $this->treeView()->render());

                $row->column(6, function (Column $column) use ($id) {
                    $form = new \OpenAdmin\Admin\Widgets\Form();
                    $form->action(admin_url('menus/' . $id . '/store-item'));

                    $menuModel = MenuItem::class;
                    $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions());
                    $form->text('title', 'Title')->rules('required');
                    $form->text('link', 'Link');
                    $form->hidden('_token')->default(csrf_token());

                    $column->append((new Box('New Item', $form))->style('success'));
                });
            });
    }

    public function editItem(Menu $menu, MenuItem $menuItem, Content $content)
    {
        $form = new \OpenAdmin\Admin\Widgets\Form();
        $form->action(admin_url('menus/' . $menu->id . '/items/' . $menuItem->id . '/update-item'));

        $menuModel = MenuItem::class;
        $form->select('parent_id', trans('admin.parent_id'))->options($menuModel::selectOptions())->value($menuItem->parent_id);
        $form->text('title', 'Title')->rules('required')->value($menuItem->title);
        $form->text('link', 'Link')->value($menuItem->link);
        $form->hidden('_token')->default(csrf_token());
        $form->hidden('_method')->default('PUT');

        return $content
            ->title('Edit menu item')
            ->description($this->description['edit'] ?? trans('admin.edit'))
            ->body($form->edit($menuItem->id));
    }

    public function updateItem(Menu $menu, MenuItem $menuItem, Request $request)
    {
        $menuItem->link = $request->input('link');
        $menuItem->title = $request->input('title');
        $menuItem->parent_id = $request->input('parent_id');
        $menuItem->save();

        return redirect(admin_url('menus/' . $menu->id . '/items'));
    }

    public function storeItem(Menu $menu, Request $request)
    {
        $data = $request->all();
        $data['menu_id'] = $menu->id;
        $data['order'] = $menu->items()->count() + 1;
        MenuItem::create($data);

        return redirect(admin_url('menus/' . $menu->id . '/items'));
    }

    public function editItemsOrder(Menu $menu, Request $request)
    {
        foreach(json_decode($request->input('_order')) as $key => $item) {
            $this->updateItemOrder($item, $key);
        }

        return redirect(admin_url('menus/' . $menu->id . '/items'));
    }

    /**
     * @return \OpenAdmin\Admin\Tree
     */
    protected function treeView()
    {
        $tree = new Tree(new MenuItem());

        $tree->disableCreate();

        $tree->branch(function ($branch) {
            $payload = "<strong>{$branch['title']}</strong>";

            if (!isset($branch['children'])) {
                /**
                 * @todo hei hi ngai kher lo tur
                 */
                // if (url()->isValidUrl($branch['link'])) {
                $link = $branch['link'];
                // } else {
                //     $link = admin_url($branch['link']);
                // }

                $payload .= "&nbsp;&nbsp;&nbsp;<a href=\"$link\" class=\"dd-nodrag\">$link</a>";
            }

            return $payload;
        });

        return $tree;
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
