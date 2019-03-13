<?php

namespace Ompmega\BlogShortcodes\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\SettingsManager;

/**
 * Shortcodes Back-end Controller
 */
class Shortcodes extends Controller
{
    public $implement = [
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.FormController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['ompmega.blogshortcodes.manage_shortcodes'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.Blog', 'blog', 'shortcodes');

        $this->addJs('/plugins/ompmega/blogshortcodes/assets/js/shortcodes.js');
    }

    public function onCreateForm()
    {
        $this->asExtension('FormController')->create();

        return $this->makePartial('create_form');
    }

    public function onCreate()
    {
        $this->asExtension('FormController')->create_onSave();

        return $this->listRefresh();
    }

    public function onUpdateForm()
    {
        $this->asExtension('FormController')->update(post('record_id'));
        $this->vars['recordId'] = post('record_id');

        return $this->makePartial('update_form');
    }

    public function onUpdate()
    {
        $this->asExtension('FormController')->update_onSave(post('record_id'));

        return $this->listRefresh();
    }

    public function onDelete()
    {
        $this->asExtension('FormController')->update_onDelete(post('record_id'));

        return $this->listRefresh();
    }
}
