define([
    'jquery',
    'mage/utils/wrapper',
    'Magento_Checkout/js/model/quote'
], function ($, wrapper,quote) {
    'use strict';
    return function (setShippingInformationAction) {
        return wrapper.wrap(setShippingInformationAction, function (originalAction, messageContainer) {
            var shippingAddress = quote.shippingAddress();
            if (shippingAddress['extension_attributes'] === undefined) {
                shippingAddress['extension_attributes'] = {};
            }
            if (shippingAddress.customAttributes != undefined) {
                $.each(shippingAddress.customAttributes , function( key, value ) {
                    if($.isPlainObject(value)){
                        value = value['value'];
                    }
                    shippingAddress['customAttributes'][key] = value;
                    shippingAddress['extension_attributes'][key] = value;
                });
            } 
            if(shippingAddress.city_id != undefined) {
                shippingAddress['extension_attributes']['city_id'] = shippingAddress.city_id;
                delete shippingAddress.city_id;
            }
            if(shippingAddress.township != undefined) {
                shippingAddress['extension_attributes']['township'] = shippingAddress.township;
                delete shippingAddress.township;
            } 
            if(shippingAddress.township_id != undefined) {
                shippingAddress['extension_attributes']['township_id'] = shippingAddress.township_id;
                delete shippingAddress.township_id;
            }

            var billingAddress = quote.billingAddress();
            if(billingAddress != undefined) {
                if (billingAddress['extension_attributes'] === undefined) {
                    billingAddress['extension_attributes'] = {};
                }
                if (billingAddress.customAttributes != undefined) {
                    $.each(billingAddress.customAttributes, function (key, value) {
                        if($.isPlainObject(value)){
                            value = value['value'];
                        }
                        billingAddress['extension_attributes'][key] = value;
                    });
                } 
                if(billingAddress.city_id != undefined) {
                    billingAddress['extension_attributes']['city_id'] = billingAddress.city_id;
                    delete billingAddress.city_id;
                }
                if(billingAddress.township != undefined) {
                    billingAddress['extension_attributes']['township'] = billingAddress.township;
                    delete billingAddress.township;
                }
                if(billingAddress.township_id != undefined) {
                    billingAddress['extension_attributes']['township_id'] = billingAddress.township_id;
                    delete billingAddress.township_id;
                }
            }
            return originalAction(messageContainer);
        });
    };
});