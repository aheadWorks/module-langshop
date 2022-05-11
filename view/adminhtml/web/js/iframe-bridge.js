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
            if (data.eventName) {
                let event = new CustomEvent(data.eventName, {detail: data});
                window.dispatchEvent(event);
            }
        }
    };

    window.addEventListener('message', function (e) {
        let event = e.data.event;

        if (iframeBridge[event]) {
            iframeBridge[event](e.data);
        }
    });
});
