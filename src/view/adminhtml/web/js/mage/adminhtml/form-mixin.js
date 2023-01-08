define([
    'jquery'
], function (jQuery) {
    'use strict';

    return function (DefaultForm) {
        var originalDependenceControllerTrackChange = FormElementDependenceController.prototype.trackChange,
            ELEMENT_ID = 'system_oauth_gmail_auth_file';

        /**
         * @override
         * @param {Object} e - event
         * @param {String} idTo - id of target element
         * @param {Object} valuesFrom - ids of master elements and reference values
         * @return
         */
        FormElementDependenceController.prototype.trackChange = function (e, idTo, valuesFrom) {
            originalDependenceControllerTrackChange.call(this, e, idTo, valuesFrom);

            if (!valuesFrom.hasOwnProperty(ELEMENT_ID)) {
                return;
            }

            var element = $(ELEMENT_ID);
            if (!element || element.type !== 'file') {
                return;
            }

            var values = valuesFrom[ELEMENT_ID].values,
                fromValue = element.value || element.defaultValue,
                isInArray = values.indexOf(fromValue) !== -1,
                isNegative = valuesFrom[ELEMENT_ID].negative,
                shouldShowUp = true;

            if (isInArray && isNegative || !isInArray && !isNegative) {
                shouldShowUp = false;
            }

            // toggle target row
            var headElement = jQuery('#' + idTo + '-head'),
                isInheritCheckboxChecked = $(idTo + '_inherit') && $(idTo + '_inherit').checked,
                target = $(idTo);

            // Account for the chooser style parameters.
            if (target === null && headElement.length === 0 && idTo.substring(0, 16) === 'options_fieldset') {
                var targetArray = $$('input[id*="' + idTo + '"]');
                var isChooser = true;

                if (targetArray !== null && targetArray.length > 0) {
                    target = targetArray[0];
                }
                headElement = jQuery('.field-' + idTo).add('.field-chooser' + idTo);
            }

            // Target won't always exist (for example, if field type is "label")
            if (target) {
                var inputs = target.up(this._config['levels_up']).select('input', 'select', 'td');
                var isAnInputOrSelect = ['input', 'select'].indexOf(target.tagName.toLowerCase()) != -1; //eslint-disable-line

                if (target.type === 'fieldset') {
                    inputs = target.select('input', 'select', 'td');
                }
            } else {
                var inputs = false;
                var isAnInputOrSelect = false;
            }

            if (shouldShowUp) {
                var currentConfig = this._config;

                if (inputs) {
                    inputs.each(function (item) {
                        // don't touch hidden inputs (and Use Default inputs too), bc they may have custom logic
                        if ((!item.type || item.type != 'hidden') && !($(item.id + '_inherit') && $(item.id + '_inherit').checked) && //eslint-disable-line
                            !(currentConfig['can_edit_price'] != undefined && !currentConfig['can_edit_price']) && //eslint-disable-line
                            !item.getAttribute('readonly') || isChooser //eslint-disable-line
                        ) {
                            item.disabled = false;
                            jQuery(item).removeClass('ignore-validate');
                        }
                    });
                }

                if (headElement.length > 0) {
                    headElement.show();

                    if (headElement.hasClass('open') && target) {
                        target.show();
                    } else if (target) {
                        target.hide();
                    }
                } else {
                    if (target) {
                        target.show();
                        headElement = jQuery('.field-' + idTo).add('.field-chooser' + idTo);
                        headElement.show();
                    }

                    if (isAnInputOrSelect && !isInheritCheckboxChecked) {
                        if (target) {
                            if (target.getAttribute('readonly')) {
                                target.disabled = true;
                            } else {
                                target.disabled = false;
                            }
                        }

                        jQuery('#' + idTo).removeClass('ignore-validate');
                    }
                }
            } else {
                if (inputs) {
                    inputs.each(function (item) {
                        // don't touch hidden inputs (and Use Default inputs too), bc they may have custom logic
                        if ((!item.type || item.type != 'hidden') && //eslint-disable-line eqeqeq
                            !($(item.id + '_inherit') && $(item.id + '_inherit').checked)
                        ) {
                            item.disabled = true;
                            jQuery(item).addClass('ignore-validate');
                        }
                    });
                }

                if (headElement.length > 0) {
                    headElement.hide();
                } else {
                    headElement = jQuery('.field-' + idTo).add('.field-chooser' + idTo);
                    headElement.hide();
                }

                if (target) {
                    target.hide();
                }

                if (isAnInputOrSelect && !isInheritCheckboxChecked) {
                    if (target) {
                        target.disabled = true;
                    }
                    jQuery('#' + idTo).addClass('ignore-validate');
                }

            }
            var rowElement = $('row_' + idTo);

            if (rowElement == undefined && target) { //eslint-disable-line eqeqeq
                rowElement = target.up(this._config['levels_up']);

                if (target.type === 'fieldset') {
                    rowElement = target;
                }
            }

            if (rowElement) {
                if (shouldShowUp) {
                    rowElement.show();
                } else {
                    rowElement.hide();
                }
            }
        };

        return DefaultForm;
    }
});
