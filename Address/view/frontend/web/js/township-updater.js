define([
    'jquery',
    'mage/template',
    'underscore',
    'jquery/ui',
    'mage/validation'
], function ($, mageTemplate, _) {
    'use strict';

    $.widget('mage.townshipUpdater', {
        options: {
            townshipTemplate:
                '<option value="<%- data.value %>" <% if (data.isSelected) { %>selected="selected"<% } %>>' +
                    '<%- data.title %>' +
                '</option>',
            isTownshipRequired: true,
            currentTownship: null
        },

        /**
         *
         * @private
         */
        _create: function () {
            this._initCityElement();

            this.currentTownshipOption = this.options.currentTownship;
            this.townshipTmpl = mageTemplate(this.options.townshipTemplate);

            var self = this,
                counter = 0;
            var timer = setInterval(function () {
                if (counter >= 15 || self.element.find('option:selected').val()) {
                    self._updateTownship(self.element.find('option:selected').val());
                    $(self.options.townshipListId).trigger('change');
                    clearInterval(timer);
                }
                counter++;
            }, 500);

            $(this.options.townshipListId).on('change', $.proxy(function (e) {
                this.setOption = false;
                this.currentTownshipOption = $(e.target).val();
                if($(e.target).val() != ''){
                    $(this.options.townshipInputId).val($(e.target).find('option:selected').text());
                }
            }, this));

            $(this.options.townshipInputId).on('focusout', $.proxy(function () {
                this.setOption = true;
            }, this));
        },

        /**
         *
         * @private
         */
        _initCityElement: function () {
            this.element.parents('div.field').show();
            this.element.on('change', $.proxy(function (e) {
                this._updateTownship($(e.target).val());
            }, this));
        },

        /**
         * Remove options from dropdown list
         *
         * @param {Object} selectElement - jQuery object for dropdown list
         * @private
         */
        _removeSelectOptions: function (selectElement) {
            selectElement.find('option').each(function (index) {
                if (index) {
                    $(this).remove();
                }
            });
        },

        /**
         * Render dropdown list
         * @param {Object} selectElement - jQuery object for dropdown list
         * @param {String} key - township code
         * @param {Object} value - township object
         * @private
         */
        _renderSelectOption: function (selectElement, key, value) {
            selectElement.append($.proxy(function () {
                var name = value.name.replace(/[!"#$%&'()*+,.\/:;<=>?@[\\\]^`{|}~]/g, '\\$&'),
                    tmplData,
                    tmpl;

                if (value.code && $(name).is('span')) {
                    key = value.code;
                    value.name = $(name).text();
                }

                tmplData = {
                    value: key,
                    title: value.name,
                    isSelected: false
                };

                if (this.options.defaultTownship === key) {
                    tmplData.isSelected = true;
                }

                tmpl = this.townshipTmpl({
                    data: tmplData
                });

                return $(tmpl);
            }, this));
        },

        /**
         * Takes clearError callback function as first option
         * If no form is passed as option, look up the closest form and call clearError method.
         * @private
         */
        _clearError: function () {
            var args = ['clearError', this.options.townshipListId, this.options.townshipInputId, this.options.postcodeId];

            if (this.options.clearError && typeof this.options.clearError === 'function') {
                this.options.clearError.call(this);
            } else {
                if (!this.options.form) {
                    this.options.form = this.element.closest('form').length ? $(this.element.closest('form')[0]) : null;
                }

                this.options.form = $(this.options.form);

                this.options.form && this.options.form.data('validator') &&
                    this.options.form.validation.apply(this.options.form, _.compact(args));

                // Clean up errors on township & zip fix
                $(this.options.townshipInputId).removeClass('mage-error').parent().find('[generated]').remove();
                $(this.options.townshipListId).removeClass('mage-error').parent().find('[generated]').remove();
                $(this.options.postcodeId).removeClass('mage-error').parent().find('[generated]').remove();
            }
        },

        /**
         * Update dropdown list based on the city selected
         *
         * @param {String} city
         * @private
         */
        _updateTownship: function (city) {
            // Clear validation error messages
            var townshipList = $(this.options.townshipListId),
                townshipInput = $(this.options.townshipInputId),
                postcode = $(this.options.postcodeId),
                label = townshipList.parent().siblings('label'),
                requiredLabel = townshipList.parents('div.field');

            this._clearError();

            // Populate state/province dropdown list if available or use input box
            if (this.options.townshipJson[city]) {
                this._removeSelectOptions(townshipList);
                $.each(this.options.townshipJson[city], $.proxy(function (key, value) {
                    this._renderSelectOption(townshipList, key, value);
                }, this));

                if (this.currentTownshipOption) {
                    townshipList.val(this.currentTownshipOption);
                }

                if (this.setOption) {
                    townshipList.find('option').filter(function () {
                        return this.text === townshipInput.val();
                    }).attr('selected', true);
                }

                if (this.options.isTownshipRequired) {
                    townshipList.addClass('required-entry').removeAttr('disabled');
                    requiredLabel.addClass('required');
                } else {
                    townshipList.removeClass('required-entry validate-select').removeAttr('data-validate');
                    requiredLabel.removeClass('required');

                    if (!this.options.optionalTownshipAllowed) { //eslint-disable-line max-depth
                        townshipList.attr('disabled', 'disabled');
                    }
                }

                townshipList.show();
                townshipInput.hide();
                label.attr('for', townshipList.attr('id'));
            } else {
                if (this.options.isTownshipRequired) {
                    townshipInput.addClass('required-entry').removeAttr('disabled');
                    requiredLabel.addClass('required');
                } else {
                    if (!this.options.optionalTownshipAllowed) { //eslint-disable-line max-depth
                        townshipInput.attr('disabled', 'disabled');
                    }
                    requiredLabel.removeClass('required');
                    townshipInput.removeClass('required-entry');
                }

                townshipList.removeClass('required-entry').prop('disabled', 'disabled').hide();
                townshipInput.show();
                label.attr('for', townshipInput.attr('id'));
            }
            // Add defaultvalue attribute to state/province select element
            townshipList.attr('defaultvalue', this.options.defaultTownship);
        }
    });

    return $.mage.townshipUpdater;
});
