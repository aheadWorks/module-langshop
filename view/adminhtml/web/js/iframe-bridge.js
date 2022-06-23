define([], function () {
    'use strict';

    let iframeBridge = {
        reload: function (data) {
            window.location.reload();
        },

        redirectTo: function (data) {
            if (data.url) {
                window.location.href = data.url;
            }
        },

        openInNewTab: function (data) {
            if (data.url) {
                window.open(data.url, '_blank');
            }
        },

        dispatchEvent: function (data) {
            if (data.event) {
                let event = new CustomEvent(data.event, {detail: data.data});
                window.dispatchEvent(event);
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
});
