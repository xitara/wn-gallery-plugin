// import './config.js';
// import 'alpinejs';
// import SimpleBar from 'simplebar';
import GLightbox from 'glightbox';
// import Rellax from 'rellax';
import { tns } from 'tiny-slider/src/tiny-slider';
// import './modules/markjs.js';
// import './modules/smooth-scroll.js';
// import './modules/timezone-offset.js';
import { qs, qsa, $on } from './utils';

$on(document, 'DOMContentLoaded', () => {
    /**
     * init all sliders
     */
    if (typeof slideConfig !== 'undefined') {
        let slider = [];

        // eslint-disable-next-line no-undef
        Object.keys(slideConfig).forEach((key) => {
            if (qs('#slide-xitara-gallery-' + key)) {
                if (typeof slider[key] === 'undefined') {
                    // eslint-disable-next-line no-undef
                    slider[key] = tns(slideConfig[key]);

                    slider[key].events.on('transitionStart', function (info) {
                        info.slideItems[info.indexCached].classList.remove('tns-slide-center');
                        info.slideItems[info.index].classList.add('tns-slide-center');
                    });
                }
            }
        });
    }

    /**
     * init glightbox
     */
    let lightbox = [];
    if (typeof lightboxConfig !== 'undefined') {
        // eslint-disable-next-line no-undef
        Object.keys(lightboxConfig).forEach((key) => {
            if (qs('.lightbox-xitara-gallery-' + key)) {
                /**
                 * remove cloned images from lightbox
                 */
                let parent = qs('.lightbox-xitara-gallery-' + key);
                qsa('li.tns-slide-cloned', parent).forEach((elm) => {
                    // eslint-disable-next-line no-undef
                    // console.log(elm);

                    let html = qs('a', elm).innerHTML;
                    elm.innerHTML = html;
                });

                // eslint-disable-next-line no-undef
                // console.log(key);

                // eslint-disable-next-line no-undef, no-unused-vars
                lightbox[key] = GLightbox(lightboxConfig[key]);

                // eslint-disable-next-line no-undef
                // console.log(lightbox[key]);
            }
        });
    }

    if (typeof lightboxSingleConfig !== 'undefined') {
        // eslint-disable-next-line no-undef
        if (qs('.glightbox') || qs(lightboxSingleConfig.selector)) {
            // eslint-disable-next-line no-undef
            // console.log(key);

            // eslint-disable-next-line no-undef, no-unused-vars
            let lightboxSingle = GLightbox(lightboxSingleConfig);

            // eslint-disable-next-line no-undef
            // console.log(lightboxConfig[key]);
            // console.log(lightbox[key]);
        }
    }
});

import '../scss/styles.scss';
