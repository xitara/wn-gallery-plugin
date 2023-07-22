<?php

namespace Xitara\Gallery\Components;

use Cms\Classes\ComponentBase;
use Xitara\Gallery\Models\Gallery as GalleryModel;

class Gallery extends ComponentBase
{
    /**
     * make gallery visible to __SELF__
     * @var GalleryModel
     */
    public $gallery;
    public $filter;
    public $cssClasses;

    public function componentDetails()
    {
        return [
            'name'        => 'xitara.gallery::lang.component.gallery.name',
            'description' => 'xitara.gallery::lang.component.gallery.description',
        ];
    }

    public function defineProperties()
    {
        return [
            'gallery' => [
                'title'       => 'xitara.gallery::lang.component.gallery.property_title',
                'description' => 'xitara.gallery::lang.component.gallery.property_description',
                'type'        => 'dropdown',
                'required'    => true,
            ],
        ];
    }

    public function getGalleryOptions()
    {
        return GalleryModel::orderBy('name', 'asc')->lists('name', 'slug');
    }

    public function onRun()
    {
        $this->addJs('/plugins/xitara/gallery/assets/js/app.js');
        $this->addCss('/plugins/xitara/gallery/assets/css/app.css');

        $gallery       = GalleryModel::where('slug', $this->property('gallery'))->first();
        $this->gallery = $this->page['gallery'] = $gallery;
        $this->filter  = $this->page['filter']  = $this->property('filter');
    }

    public function onRender()
    {
        $this->addJs('/plugins/xitara/gallery/assets/js/app.js');
        $this->addCss('/plugins/xitara/gallery/assets/css/app.css');

        /**
         * you can override gallery-id from properties with a gallery as component param like
         * {% component 'storeGallery' gallery=1 %}
         */
        if (is_numeric($this->property('gallery'))) {
            $gallery = GalleryModel::find($this->property('gallery'));
        } else {
            $gallery = GalleryModel::where('slug', $this->property('gallery'))->first();
        }

        $this->gallery = $this->page['gallery'] = $gallery;
        $this->filter  = $this->page['filter']  = $this->property('filter');
    }
}
