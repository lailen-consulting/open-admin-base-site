<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use Illuminate\Support\Str;
use Lailen\OpenAdmin\Site\Models\Album;
use Lailen\OpenAdmin\Site\Models\Config;
use Lailen\OpenAdmin\Site\Models\Menu;
use Lailen\OpenAdmin\Site\Models\Page;
use Lailen\OpenAdmin\Site\Models\Post;
use Lailen\OpenAdmin\Site\Models\PostCategory;
use OpenAdmin\Admin\Layout\Content;

class SettingsController extends AdminController
{
    public function index(Content $content)
    {
        $form = $this->form();
        if ($this->hasHooks('alterForm')) {
            $form = $this->callHooks('alterForm', $form);
        }

        return $content
            ->title('Settings')
            ->body($form);
    }

    public function updateSettings(Request $request)
    {
        foreach($request->all() as $key => $value) {
            Config::where('key', $key)->update(['value' => $value]);
        }

        return redirect('/admin/settings');
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Config());
        $form->setAction('settings');
        $form->setTitle('Settings Form');

        foreach(Config::get() as $config) {
            $this->renderSetting($config, $form);
        }

        return $form;
    }

    public function renderSetting($config, $form)
    {
        $key = $config->key;
        $label = $config->title;

        $options = null;

        if (json_decode($config->options)) {
            $options = json_decode($config->options, true);
        }

        switch($config->type) {
            case 'single-menu':
                $items = Menu::all()->pluck('name', 'id');
                $form->select($key, $label)->options($items)->value($config->value);
                break;
            case 'single-page':
                $items = Page::all()->pluck('title', 'id');
                $form->select($key, $label)->options($items)->value($config->value);
                break;
            case 'single-album':
                $options = Album::all()->pluck('name', 'id');
                $form->select($key, $label)->options($options)->value($config->value);
                break;
            case 'checkbox':
                $form->checkbox($key, $label)
                    ->options($options['options'])
                    ->default(isset($options['default']) ? $options['default'] : '')
                    ->value($config->value);
                break;
            case 'radio':
                $form->radio($key, $label)
                    ->options($options['options'])
                    ->default(isset($options['default']) ? $options['default'] : '')
                    ->value($config->value);
                break;
            case 'select':
                $form->select($key, $label)
                    ->options($options['options'])
                    ->default(isset($options['default']) ? $options['default'] : '')
                    ->value($config->value);
                break;
            default: {
                $form->{$config->type}($config->key, $config->title)->value($config->value);
                break;
            }
        }

    }
}
