import config from './config.js';
import Mark from 'mark.js';

/**
 * initiate list of event listeners
 *
 * @type {Array}
 */
let _listeners = [];

/**
 * querySelector wrapper
 *
 * @param {string} selector Selector to query
 * @param {Element} [scope] Optional scope element for the selector
 */
export const qs = (selector, scope) => {
    return (scope || document).querySelector(selector);
};

/**
 * querySelectorAll wrapper
 *
 * @param {string} selector Selector to query
 * @param {Element} [scope] Optional scope element for the selector
 */
export const qsa = (selector, scope) => {
    return [...(scope || document).querySelectorAll(selector)];
};

/**
 * addEventListener wrapper
 *
 * @param {Element|Window} target Target Element
 * @param {string} type Event name to bind to
 * @param {Function} callback Event callback
 * @param {boolean} [capture] Capture the event
 */
export const $on = (target, type, callback, capture) => {
    _listeners.push({ target: target, type: type, callback: callback, capture: !!capture });
    target.addEventListener(type, callback, !!capture);
};

/**
 * removeEventListener
 *
 * @param {Element|Window} target Target Element
 * @param {string} type Event name to remove
 */
export const $off = (target, type) => {
    for (let index in _listeners) {
        let item = _listeners[index];

        // let target = item.target;
        // let type = item.type;
        // let listener = item.listener;

        if (target == item.target && type == item.type) {
            target.removeEventListener(type, item.callback);
        }
    }
};

/**
 * trigger event
 *
 * @param  {Element} el   element to trigger
 * @param  {string} type event-type (e.g. click) to trigger
 */
export const $trigger = (target, type) => {
    if (target) {
        target.dispatchEvent(new Event(type));
    }
};

/**
 * Scroll to a given position or a given element/selector
 *
 * @param  {mixed} position|element     position in pixel from top or element
 * @param  {Number} left     position from left
 * @param  {String} behavior auto|smooth, default: smooth
 */
export const $scroll = (position, left = 0, behavior = 'smooth') => {
    if (isNaN(position) && qs(position)) {
        let box = qs(position).getBoundingClientRect();
        position = box.top + window.scrollY - config.scrollOffset;
    }

    if (position < 1) {
        return;
    }

    window.scrollTo({
        top: position,
        left: left,
        behavior: behavior,
    });
};

/**
 * Attach a handler to an event for all elements matching a selector.
 *
 * @param {Element} target Element which the event must bubble to
 * @param {string} selector Selector to match
 * @param {string} type Event name
 * @param {Function} handler Function called when the event bubbles to target
 *                           from an element matching selector
 * @param {boolean} [capture] Capture the event
 */
export const $delegate = (target, selector, type, handler, capture) => {
    const dispatchEvent = (event) => {
        const targetElement = event.target;
        const potentialElements = target.querySelectorAll(selector);

        for (let i = potentialElements.length; i >= 0; i--) {
            if (potentialElements[i] === targetElement) {
                handler.call(targetElement, event);
                break;
            }
        }
    };

    $on(target, type, dispatchEvent, !!capture);
};

/**
 * fetch data from url
 *
 * for use with cors payload you have to enable put and options in the routing of laravel etc.
 * Route::match(['put', 'options'], '/', function () {});
 *
 * @autor   mburghammer
 * @date    2020-11-23T22:38:07+01:00
 * @version 0.0.1
 * @since   0.0.1
 * @param   {string}    url     url to fetch data from
 * @param   {String}    method  method to fetch (GET, POST, PUT, OPTIONS).
 * @param   {Object}    payload json payload
 * @param   {String}    mode    mode like cors, no-cors etc.
 * @return  {object}            json-object
 */
export const $fetch = async (url, method = 'POST', payload = {}, mode = 'cors') => {
    let data = await fetch(url, {
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
        },
        method: method,
        mode: mode,
        body: JSON.stringify(payload),
    })
        .then((response) => {
            if (response.ok) {
                return Promise.resolve(response);
            } else {
                return Promise.reject(new Error('Failed to load'));
            }
        })
        .then((response) => response.json())
        .then((data) => {
            return data;
        })
        .catch(function (error) {
            return Promise.reject(new Error(error));
        });

    return data;
};

/**
 * init mark.js to highlight search-phrases (?highlight=[TEXT])
 */
export const $mark = () => {
    const urlParams = new URLSearchParams(window.location.search);
    const highlight = urlParams.get('highlight');

    /**
     * highlight search results
     * active with 'highlight=[TEXT]' as query-paramter
     */
    if (highlight !== null) {
        const instance = new Mark('main');
        instance.mark(highlight, {
            separateWordSearch: false,
        });

        /**
         * scroll first element in center of viewport
         */
        qs('mark[data-markjs]').scrollIntoView({
            behavior: 'smooth',
            block: 'center',
            inline: 'center',
        });
    }
};

/**
 * set cookie
 *
 * @param  {string} $var      cookie var
 * @param  {string} $value    cookie value
 * @param  {integer} $expire cookie expiring time in days. default: 30 days
 */
export const $sc = ($var, $value, $expire = 30) => {
    let date = new Date(this.valueOf());
    date.setDate(date.getDate() + $expire);

    if (!document.cookie.split('; ').find((row) => row.startsWith($var + '=' + $value))) {
        document.cookie = $var + '=' + $value + '; expires=' + date.toUTCString();
    }
};

/**
 * check cookie
 *
 * @param  {string} $var      cookie var
 * @param  {string} $value    cookie value
 * @return {null|bool|string}]     value if cookie exists and $value is null, true if value is given and same value as in cookie, false if value is given and not the same value as in cookie, null if $var not exists
 */
export const $cc = ($var, $value = null) => {
    if (document.cookie.split(';').some((item) => item.trim().startsWith($var + '='))) {
        const value = document.cookie
            .split('; ')
            .find((row) => row.startsWith($var + '='))
            .split('=')[1];

        if ($value === null) {
            return value;
        }

        if ($value == value) {
            return true;
        }

        return false;
    }

    return null;
};

/**
 * delete cookie
 *
 * @param  {string} $var      cookie var
 * @param  {string} $value    cookie value
 * @return {bool}     true if cookie exists, false if no cookie found
 */
export const $dc = ($var, $value) => {
    if (!document.cookie.split('; ').find((row) => row.startsWith($var + '=' + $value))) {
        document.cookie = $var + '=' + $value + '; expires=Thu, 01 Jan 1970 00:00:01 GMT';
        return true;
    }

    return false;
};
