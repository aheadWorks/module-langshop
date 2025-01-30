define([
    'jquery'
], function ($) {
    'use strict';

    let iframeBridge = {
        options: {
            redirectUrl: ''
        },

        /**
         * @property {HTMLElement} iframe
         */
        iframe: null,
        height: 600,

        reload: function (data) {
            window.location.reload();
        },

        /**
         * @param {string} data.url
         */
        redirectTo: function (data) {
            if (data.url) {
                window.location.href = data.url;
            }
        },

        /**
         * @param {string} data.path
         */
        redirectToByPath: function (data) {
            $.ajax({
                url: this.options.redirectUrl,
                type: 'get',
                dataType: 'json',
                data: {
                    path: data.path
                }
            }).success(function (response) {
                if (response.url) {
                    window.parent.location.href = response.url;
                }
            });
        },

        /**
         * @param {string} data.url
         */
        openInNewTab: function (data) {
            if (data.url) {
                window.open(data.url, '_blank');
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
        let messageObject = typeof (e.data) === 'string'
            ? JSON.parse(e.data)
            : e.data;

        let eventName = messageObject.event || '';
        let eventData = messageObject.data || {};

        if (iframeBridge[eventName]) {
            iframeBridge[eventName](eventData);
        }
    });

    /**
     * @param {object} settings
     * @param {HTMLElement} iframe
     */
    return function (settings, iframe) {
        iframeBridge.iframe = iframe;
        iframeBridge.options = settings;
    };
});
