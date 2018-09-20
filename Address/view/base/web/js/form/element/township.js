/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select'
], function (_, registry, Select) {
    'use strict';

    return Select.extend({
        defaults: {
            skipValidation: false,
            imports: {
                update: '${ $.parentName }.city_id:value'
            }
        },

        /**
         * @param {String} value
         */
        update: function (value) {
            var source = this.initialOptions,
                field = this.filterBy.field,
                result;

            result = _.filter(source, function (item) {
                return item[field] === value || item.value === '';
            });

            if (result.length > 0 && value != undefined && value != '') {
                this.filter(value, field);
                this.setVisible(true);
                this.toggleInput(false);
            } else {
                this.setVisible(false);
                this.toggleValue();
                this.toggleInput(true);
            }
        },

        /**
         * Filters 'initialOptions' property by 'field' and 'value' passed,
         * calls 'setOptions' passing the result to it
         *
         * @param {*} value
         * @param {String} field
         */
        filter: function (value, field) {
            var cities = registry.get(this.parentName + '.' + 'city_id');
            if (cities && value != undefined && value != ''){
                this._super(value, field);
            } else {
                this.setVisible(false);
                this.toggleValue();
                this.toggleInput(true);
            }
        },

        /**
         * Callback that fires when 'value' property is updated.
         */
        onUpdate: function () {
            this._super();
            var value = this.value(),
                result;
            result = this.indexedOptions[value];
            if(result != undefined){
                registry.get(this.customName, function (input) {
                    input.value(result.label);
                });
            }
        },

        /**
         * Change value for input.
         */
        toggleValue: function () {
            registry.get(this.customName, function (input) {
                input.value('');
            });
        }
    });
});