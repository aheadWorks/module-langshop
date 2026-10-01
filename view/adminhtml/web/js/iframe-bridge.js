define([
    'jquery'
], function ($) {
    'use strict';

    let iframeBridge = {
        options: {
            redirectUrl: '',
            allowedHosts: ['langshop.io']
        },

        /**
         * @property {HTMLElement} iframe
         */
        iframe: null,
        height: 600,

        /**
         * @returns {string[]}
         */
        getAllowedHosts: function () {
            if (this.options.allowedHosts && this.options.allowedHosts.length) {
                return this.options.allowedHosts;
            }
            if (this.iframe && this.iframe.src) {
                try {
                    return [new URL(this.iframe.src).hostname];
                } catch (e) {
                    return ['langshop.io'];
                }
            }

            return ['langshop.io'];
        },

        /**
         * @param {string} origin
         * @returns {boolean}
         */
        isAllowedOrigin: function (origin) {
            let host;

            try {
                host = new URL(origin).hostname.toLowerCase();
            } catch (e) {
                return false;
            }

            return this.getAllowedHosts().some(function (allowed) {
                allowed = String(allowed).toLowerCase();

                return host === allowed || host.endsWith('.' + allowed);
            });
        },

        /**
         * Allow Magento admin URLs and http(s) destinations requested by Langshop.
         * External hosts (e.g. Stripe pricing) are part of the product workflow.
         *
         * @param {string} url
         * @returns {boolean}
         */
        isNavigableUrl: function (url) {
            let parsed;

            try {
                parsed = new URL(url, window.location.href);
            } catch (e) {
                return false;
            }

            return parsed.protocol === 'http:' || parsed.protocol === 'https:';
        },

        reload: function (data) {
            window.location.reload();
        },

        /**
         * @param {string} data.url
         */
        redirectTo: function (data) {
            if (data.url && this.isNavigableUrl(data.url)) {
                window.location.href = data.url;
            }
        },

        /**
         * @param {string} data.path
         */
        redirectToByPath: function (data) {
            if (!data.path) {
                return;
            }

            $.ajax({
                url: this.options.redirectUrl,
                type: 'get',
                dataType: 'json',
                data: {
                    path: data.path
                }
            }).success(function (response) {
                if (response.url && iframeBridge.isNavigableUrl(response.url)) {
                    window.parent.location.href = response.url;
                }
            });
        },

        /**
         * @param {string} data.url
         */
        openInNewTab: function (data) {
            if (data.url && this.isNavigableUrl(data.url)) {
                window.open(data.url, '_blank', 'noopener,noreferrer');
            }
        },

        /**
         * @param {string} data.event
         * @param {object} data.data
         */
        dispatchEvent: function (data) {
            if (data.event) {
                let event = new CustomEvent(data.event, {detail: data.data});
                window.dispatchEvent(event);
            }
        },

        /**
         * @param {number} data.width
         * @param {number} data.height
         */
        updateIframeSize: function (data) {
            if (this.iframe && data.height) {
                let height = data.height > this.height ? data.height : this.height;
                this.iframe.height = height.toString();
            }
        }
    };

    window.addEventListener('message', function (e) {
        let messageObject,
            eventName,
            eventData;

        if (!iframeBridge.isAllowedOrigin(e.origin)) {
            return;
        }

        try {
            messageObject = typeof (e.data) === 'string'
                ? JSON.parse(e.data)
                : e.data;
        } catch (error) {
            return;
        }

        if (!messageObject || typeof messageObject !== 'object') {
            return;
        }

        eventName = messageObject.event || '';
        eventData = messageObject.data || {};

        if (Object.prototype.hasOwnProperty.call(iframeBridge, eventName)
            && typeof iframeBridge[eventName] === 'function'
            && eventName !== 'isAllowedOrigin'
            && eventName !== 'isNavigableUrl'
            && eventName !== 'getAllowedHosts'
        ) {
            iframeBridge[eventName](eventData);
        }
    });

    /**
     * @param {object} settings
     * @param {HTMLElement} iframe
     */
    return function (settings, iframe) {
        iframeBridge.iframe = iframe;
        iframeBridge.options = $.extend({}, iframeBridge.options, settings);
    };
});
