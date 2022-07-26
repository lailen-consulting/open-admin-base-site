<?php

namespace Lailen\OpenAdmin\Site\Http\Controllers;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use Lailen\OpenAdmin\Site\Models\Album;
use Lailen\OpenAdmin\Site\Models\Config;
use Lailen\OpenAdmin\Site\Models\Menu;
use Lailen\OpenAdmin\Site\Models\Page;
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
            $setting = Config::where('key', $key)->first();
            if(!$setting) {
                continue;
            }

            if ($setting->type == 'post-categories') {
                foreach($value as $key => $id) {
                    if (!$id) {
                        unset($value[$key]);
                    }
                }
            }

            if ($setting->type == 'file') {
                $fileName = $key . '.' . $request->file($key)->extension();
                $request->file($key)->storeAs('settings', $fileName, 'admin');
                $value = 'settings/' . $fileName;
            }

            $setting->value = $value;
            $setting->save();
        }

        return redirect(admin_url('ll_settings'));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Config());
        $form->setAction('ll_settings');
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
        $value = $config->value;

        $options = null;

        if (json_decode($config->options)) {
            $options = json_decode($config->options, true);
        }

        switch($config->type) {
            case 'single-menu':
                $items = Menu::all()->pluck('name', 'id');
                $form->select($key, $label)->options($items)->value($value);
                break;
            case 'single-page':
                $items = Page::all()->pluck('title', 'id');
                $form->select($key, $label)->options($items)->value($value);
                break;
            case 'single-album':
                $options = Album::all()->pluck('name', 'id');
                $form->select($key, $label)->options($options)->value($value);
                break;
            case 'checkbox':
                $form->checkbox($key, $label)
                    ->options($options['options'])
                    ->default(isset($options['default']) ? $options['default'] : '')
                    ->value($value);
                break;
            case 'radio':
                $form->radio($key, $label)
                    ->options($options['options'])
                    ->default(isset($options['default']) ? $options['default'] : '')
                    ->value($value);
                break;
            case 'select':
                $form->select($key, $label)
                    ->options($options['options'])
                    ->default(isset($options['default']) ? $options['default'] : '')
                    ->value($value);
                break;
            case 'file':
                $form->file($key, $label)
                    ->pick()
                    ->value($value);
                break;
            case 'post-categories':
                $items = PostCategory::all()->pluck('name', 'id');
                $value = json_decode($value);
                $form->multipleSelect($key, $label)->options($items)->value($value);
                break;
            default: {
                $form->{$config->type}($config->key, $config->title)->value($value);
                break;
            }
        }

    }
}
