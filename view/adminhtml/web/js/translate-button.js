define([
    'jquery',
    'mage/translate',
    'mage/backend/bootstrap'
], function ($) {
    'use strict';

    /**
     * Shows notification in case of successful request
     */
    function showNotification() {
        $('body').notification('clear').notification('add', {
            message: $.mage.__('Translation started. Please wait.'),

            /**
             * @param {String} message
             */
            insertMethod: function (message) {
                let $wrapper = $('<div></div>').html(message);

                $('.page-main-actions').after($wrapper);
            }
        });
    }

    return function (config, element) {
        $(element).on('click', function () {
            $.ajax({
                url: config.url,
                method: 'post',
                data: config.params,
                beforeSend: false,
                success: showNotification
            });

            return false;
        });
    };
});
