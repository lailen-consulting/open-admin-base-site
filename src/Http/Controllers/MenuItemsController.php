<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Lailen\OpenAdmin\Site\Models\MenuItem;
use Illuminate\Routing\Controller;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Layout\Column;
use OpenAdmin\Admin\Show;
use OpenAdmin\Admin\Layout\Content;
use OpenAdmin\Admin\Layout\Row;
use OpenAdmin\Admin\Traits\HasCustomHooks;
use OpenAdmin\Admin\Tree;
use OpenAdmin\Admin\Widgets\Box;

class MenuItemsController extends Controller
{

    use HasCustomHooks;

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Menu Items';

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title;
    }

    public function index(Content $content)
    {
        $grid = $this->grid();
        if ($this->hasHooks('alterGrid')) {
            $grid = $this->callHooks('alterGrid', $grid);
        }

        return $content
            ->title('Menu')
            ->description('Edit')
            ->row(function (Row $row) {
                $row->column(6, $this->grid());
                $row->column(6, $this->treeView()->render());
            });
    }

    /**
     * Show interface.
     *
     * @param mixed   $id
     * @param Content $content
     *
     * @return Content
     */
    public function show($menuId, $menuItemId, Content $content)
    {
        $detail = $this->detail($menuItemId);
        if ($this->hasHooks('alterDetail')) {
            $detail = $this->callHooks('alterDetail', $detail);
        }

        return $content
            ->title($this->title())
            ->description($this->description['show'] ?? trans('admin.show'))
            ->body($detail);
    }

    public function create(Content $content)
    {
        $id = request()->route('ll_menu');

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
     * Edit interface.
     *
     * @param mixed   $menuId
     * @param mixed   $menuItemId
     * @param Content $content
     *
     * @return Content
     */
    public function edit($menuId, $menuItemId, Content $content)
    {
        $form = $this->form();
        if ($this->hasHooks('alterForm')) {
            $form = $this->callHooks('alterForm', $form);
        }

        return $content
            ->title($this->title())
            ->description($this->description['edit'] ?? trans('admin.edit'))
            ->body($form->edit($menuItemId));
    }


    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new MenuItem());
        $grid->model()->where('menu_id', request()->route('ll_menu'));

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
        $show->field('title', __('Title'));
        $show->field('link', __('Link'));
        $show->field('image', __('Image'))->image('/storage/admin/', 200, 200);
        $show->field('description', __('Description'));
        $show->field('icon', __('Icon'));
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
        $menuId = request()->route('ll_menu');
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
            return $q->where('menu_id', request()->route('ll_menu'));
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

    /**
     * Returns the form with possible callback hooks.
     *
     * @return \OpenAdmin\Admin\Form;
     */
    public function getForm()
    {
        $form = $this->form();
        if (method_exists($this, 'hasHooks') && $this->hasHooks('alterForm')) {
            $form = $this->callHooks('alterForm', $form);
        }

        return $form;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return mixed
     */
    public function store()
    {
        return $this->getForm()->store();
    }

    public function update($id, $menuItemId)
    {
        return $this->getForm()->update($menuItemId);
    }

    public function destroy($id, $menuItemId)
    {
        return $this->getForm()->destroy($menuItemId);
    }
}
