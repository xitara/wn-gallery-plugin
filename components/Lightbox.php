<?php

namespace Xitara\Gallery\Components;

use Cms\Classes\ComponentBase;

class Lightbox extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Lightbox Component',
            'description' => 'No description provided yet...',
        ];
    }

    public function defineProperties()
    {
        return [
            'selector' => [
                'title'       => 'xitara.gallery::lang.component.gallery.selector_title',
                'description' => 'xitara.gallery::lang.component.gallery.selector_description',
                'type'        => 'string',
            ],
            'is_loop'  => [
                'title'       => 'xitara.gallery::lang.component.gallery.loop_title',
                'description' => 'xitara.gallery::lang.component.gallery.loop_description',
                'type'        => 'checkbox',
            ],
        ];
    }

    public function onRun()
    {
        $this->addJs('/plugins/xitara/gallery/assets/js/app.js');
        $this->addCss('/plugins/xitara/gallery/assets/css/app.css');
    }

    public function onRender()
    {
        $this->page['selector'] = $this->property('selector', null);
        $this->page['is_loop']  = $this->property('is_loop', false);
    }
}
