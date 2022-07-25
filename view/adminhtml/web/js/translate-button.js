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
            ajaxParams: {}
        },

        _create: function () {
            document.getElementById('translate').disabled = this.options.isDisabled;
            this.element.on('click', function () {
                document.getElementById('translate').disabled = true;
                this.sendTranslate();
            }.bind(this));
        },

        sendTranslate: function () {
            this.getAjax(this.options.translateUrl).done(
                /**
                 * @param {boolean} data.success
                 */
                function () {
                    this.showMessage();
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

        showMessage: function () {
            $('body').notification('clear').notification('add', {
                message: this.options.successMessage,

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
