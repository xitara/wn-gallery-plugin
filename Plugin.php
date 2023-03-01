<?php

namespace Xitara\Gallery;

use App;
use Backend;
use BackendMenu;
use Event;
use Xitara\Gallery\Models\Gallery;
use System\Classes\PluginBase;
use System\Classes\PluginManager;
use Winter\Pages\Classes\Page;

/**
 * Gallery Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = ['Xitara.TwigExtender'];

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name' => 'xitara.gallery::lang.plugin.name',
            'description' => 'xitara.gallery::lang.plugin.description',
            'author' => 'xitara.gallery::core.plugin.author',
            'homepage' => 'xitara.gallery::core.plugin.homepage',
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
        if (PluginManager::instance()->exists('Xitara\Nexus') === true) {
            BackendMenu::registerContextSidenavPartial(
                'Xitara.Gallery',
                'gallery',
                '$/xitara/nexus/partials/_sidebar.htm'
            );
        }
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
        // Check if we are currently in backend module.
        if (!App::runningInBackend()) {
            return;
        }

        /**
         * add items to sidemenu
         */
        if (PluginManager::instance()->exists('Xitara\Nexus') === true) {
            Event::listen('backend.page.beforeDisplay', function ($controller, $action, $params) {
                $namespace = (new \ReflectionObject($controller))->getNamespaceName();

                if ($namespace == 'Xitara\Gallery\Controllers') {
                    \Xitara\Nexus\Plugin::getSideMenu('Xitara.Gallery', 'gallery');
                }
            });
        }

        /**
         * add dropdown options to static pages
         */
        if (PluginManager::instance()->exists('Winter\Pages') === true) {
            \Winter\Pages\Classes\Page::extend(function ($model) {
                $model->addDynamicMethod('getGalleryOptions', function () {
                    $gallery = Gallery::orderBy('name', 'asc')->lists('name', 'slug');
                    $gallery = array_merge(
                        [
                            'none' => '--- ' .
                                e(trans('xitara.gallery::lang.gallery.no_gallery')) .
                                ' ---',
                        ],
                        $gallery
                    );

                    return $gallery;
                });
            });
        }
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Xitara\Gallery\Components\Gallery' => 'gallery',
            'Xitara\Gallery\Components\Lightbox' => 'lightbox',
        ];
    }

    public function registerPageSnippets()
    {
        return [
            'Xitara\Gallery\Components\Gallery' => 'gallery',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        $permissions = [
            'xitara.gallery.create' => [
                'tab' => 'Xitara Gallery',
                'label' => 'Create Galleries',
            ],
            'xitara.gallery.edit' => [
                'tab' => 'Xitara Gallery',
                'label' => 'Edit Galleries',
            ],
        ];

        /**
         * add gallery permission for each gallery
         */
        foreach (Gallery::get() as $gallery) {
            // var_dump($gallery->id);

            $permissions['xitara.gallery.gallery_edit.' . $gallery->id] = [
                'tab' => 'Galleries',
                'label' => 'Edit Gallery "' . $gallery->name . '"" (' . $gallery->id . ')',
            ];
        }
        // exit;
        // var_dump($permissions);
        return $permissions;
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        $label = 'xitara.gallery::lang.component.gallery.name';

        if (PluginManager::instance()->exists('Xitara\Nexus') === true) {
            $label .= '::hidden';
        }

        return [
            'gallery' => [
                'label' => $label,
                'url' => Backend::url('xitara/gallery/galleries'),
                'icon' => 'icon-leaf',
                'iconSvg' => 'plugins/xitara/gallery/assets/images/icon-gallery.svg',
                'permissions' => ['xitara.gallery.*'],
                'order' => 500,
            ],
        ];
    }

    /**
     * inject into sidemenu
     * @autor   mburghammer
     * @date    2020-09-22T15:17:28+02:00
     *
     * @see Xitara\Nexus::getSideMenu
     * @return  array                   sidemenu-data
     */
    public static function injectSideMenu()
    {
        $i = 0;
        return [
            'gallery.galleries' => [
                'label' => 'xitara.gallery::lang.submenu.galleries',
                'url' => Backend::url('xitara/gallery/galleries'),
                'icon' => 'icon-picture-o',
                'permissions' => ['xitara.gallery.*'],
                'attributes' => [
                    'group' => 'xitara.gallery::lang.submenu.label',
                ],
                'order' => \Xitara\Nexus\Plugin::getMenuOrder('xitara.gallery') + $i++,
            ],
        ];
    }

    /**
     * get gallery options in themes as dropdown
     * with
     * {dropdown name="gallery" label="Gallery"
     * options="\Xitara\Gallery\Plugin::getGalleryOptions"}{/dropdown}
     * or similar
     *
     * @return array list of galleries
     */
    public static function getGalleryOptions()
    {
        $gallery = Gallery::orderBy('name', 'asc')->lists('name', 'slug');
        $gallery = array_merge([
            'none' => '--- ' . e(trans('xitara.gallery::lang.gallery.no_gallery')) . ' ---',
        ], $gallery);

        return $gallery;
    }
}
