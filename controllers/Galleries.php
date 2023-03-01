<?php

namespace Xitara\Gallery\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use System\Classes\PluginManager;

/**
 * Galleries Back-end Controller
 */
class Galleries extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    /**
     * @var string Configuration file for the `FormController` behavior.
     */
    public $formConfig = 'config_form.yaml';

    /**
     * @var string Configuration file for the `ListController` behavior.
     */
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        if (PluginManager::instance()->exists('Xitara\Nexus') === true) {
            BackendMenu::setContext('Xitara.Gallery', 'gallery', 'nexus.galleries');
        } else {
            BackendMenu::setContext('Xitara.Gallery', 'gallery', 'galleries');
        }
    }

    // public function index()
    // {
    //     $this->pageTitle = trans_choice('xitara.gallery::core.update_m', 2, [
    //         'model' => trans('xitara.gallery::lang.submenu.label'),
    //     ]);

    //     $this->asExtension('ListController')->index();
    // }

    // public function update($id = null)
    // {
    //     $this->pageTitle = trans_choice('xitara.gallery::core.update_m', 1, [
    //         'model' => trans('xitara.gallery::lang.submenu.label'),
    //     ]);

    //     $this->asExtension('FormController')->update($id);
    // }
}
