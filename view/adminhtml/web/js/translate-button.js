define([
    'jquery',
    'jquery/ui',
    'mage/translate',
    'mage/backend/bootstrap'
], function ($) {
    'use strict';

    $.widget('mage.translateButton', {
        options: {
            isDisabled: false,
            translateUrl: '',
            successMessage: '',
            failedMessage: '',
            ajaxParams: {}
        },

        _create: function () {
            this.element.attr('disabled', this.options.isDisabled);
            this.element.on('click', function () {
                this.element.attr('disabled', true);
                this.sendTranslate();
            }.bind(this));
        },

        sendTranslate: function () {
            this.getAjax(this.options.translateUrl).done(
                /**
                 * @param {boolean} data.success
                 */
                function (data) {
                    if (data?.code && data?.code > 400) {
                        var message =  data.message ?? this.options.failedMessage;

                        this.element.attr('disabled', false);

                        this.showMessage(message, true);
                    } else {
                        this.showMessage();
                    }
                }.bind(this)
            );
        },

        /**
         * @param {string} url
         */
        getAjax: function (url) {
            return $.post({
                url: url,
                dataType: 'json',
                data: this.options.ajaxParams
            });
        },

        showMessage: function (message = '', isError = false) {
            var notificationMessage = message || this.options.successMessage;

            $('body').notification('clear').notification('add', {
                message: $.mage.__(notificationMessage),
                error: isError,

                /**
                 * @param {string} message
                 */
                insertMethod: function (message) {
                    let $wrapper = $('<div></div>').html(message);

                    $('.page-main-actions').after($wrapper);
                }
            });
        }
    });

    return $.mage.translateButton;
});
