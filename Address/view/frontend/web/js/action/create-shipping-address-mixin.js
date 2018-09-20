define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';
    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction, addressData) {
            if (addressData.custom_attributes != undefined) {
                $.each(addressData.custom_attributes , function(key, value) {
                    if(typeof value == 'string'){
                        addressData['custom_attributes'][key] = {'attribute_code':key,'value':value};
                    }
                });
            }
            return originalAction(addressData);
        });
    };
});