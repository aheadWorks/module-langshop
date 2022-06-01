define([
    'jquery',
    'jquery/ui',
    'mage/translate',
    'mage/backend/bootstrap'
], function ($) {
    'use strict';

    $.widget('mage.translateButton', {
        options: {
            translateUrl: '',
            statusUrl: '',
            ajaxParams: {},
            errorMessage: $.mage.__('A technical problem with the server created an error.'),
            successMessage: $.mage.__('Translations are ready. Enjoy!'),
            statusCheckFrequency: 2000
        },

        _create: function () {
            this.element.on('click', function () {
                this.showLoader();

                this.sendTranslate(function () {
                    this.interval = setInterval(
                        this.checkStatus.bind(this),
                        this.options.statusCheckFrequency
                    );
                }.bind(this));

                return false;
            }.bind(this));
        },

        /**
         * @param {function} callback
         */
        sendTranslate: function (callback) {
            this.getAjax(this.options.translateUrl).done(

                /**
                 * @param {boolean} data.success
                 */
                function (data) {
                    if (!data.success) {
                        this.showMessage(true);
                    } else {
                        callback();
                    }
                }.bind(this)
            );
        },

        checkStatus: function () {
            this.getAjax(this.options.statusUrl).done(

                /**
                 * @param {boolean} data.success
                 */
                function (data) {
                    if (data.success) {
                        this.showMessage(false);
                        clearInterval(this.interval);
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

        /**
         * @param {boolean} error
         */
        showMessage: function (error) {
            this.hideLoader();

            $('body').notification('clear').notification('add', {
                error: error,
                message: error ?
                    this.options.errorMessage :
                    this.options.successMessage,

                /**
                 * @param {string} message
                 */
                insertMethod: function (message) {
                    let $wrapper = $('<div></div>').html(message);

                    $('.page-main-actions').after($wrapper);
                }
            });
        },

        showLoader: function () {
            this.element.trigger('processStart');
        },

        hideLoader: function () {
            this.element.trigger('processStop');
        }
    });

    return $.mage.translateButton;
});
