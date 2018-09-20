define(['mage/utils/wrapper'], function (wrapper) {
    'use strict';
    return function (customerAddress) {
        return wrapper.wrap(customerAddress, function (originalAction, addressData) {
            var address = originalAction(addressData);
            address.city_id = addressData.city_id
            address.township = addressData.township;
            address.township_id = addressData.township_id;
            return address;
        });
    };
});