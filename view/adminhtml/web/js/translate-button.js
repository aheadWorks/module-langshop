define([
    'jquery',
    'mage/backend/bootstrap'
], function ($) {
    'use strict';

    /**
     * Shows notification once the request has been processed
     *
     * @param {Boolean} data.error
     * @param {String} data.message
     */
    function showNotification(data) {
        $('body').notification('clear').notification('add', {
            error: data.error,
            message: data.message,

            /**
             * @param {String} message
             */
            insertMethod: function (message) {
                let $wrapper = $('<div></div>').html(message);

                $('.page-main-actions').after($wrapper);
            }
        });
    }

    /**
     * @param {String} config.url
     * @param {Object} config.params
     * @param {HTMLElement} element
     */
    return function (config, element) {
        $(element).on('click', function () {
            $.post(config.url, config.params, showNotification, 'json');

            return false;
        });
    };
});
