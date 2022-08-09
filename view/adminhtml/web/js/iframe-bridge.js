define([], function () {
    'use strict';

    let iframeBridge = {

        /**
         * @property {HTMLElement} iframe
         */
        iframe: null,

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
            if (this.iframe) {
                if (data.height) {
                    this.iframe.style.height = data.height + 'px';
                }
            }
        }
    };

    window.addEventListener('message', function (e) {
        let messageObject = JSON.parse(e.data);
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
    };
});