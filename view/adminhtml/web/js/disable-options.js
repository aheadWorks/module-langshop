define(['jquery'], function ($) {
    'use strict';

    /**
     * Disables options in the select
     *
     * @param {Array} optionToDisable
     * @param {HTMLElement} select
     */
    return function (optionToDisable, select) {
        optionToDisable.forEach(function (option) {
            $(select).find('option[value='+option+']')
                .removeAttr('selected')
                .attr('disabled', true);
        });
    };
});
