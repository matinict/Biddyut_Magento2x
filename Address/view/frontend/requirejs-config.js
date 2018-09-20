var config = {
    config: {
        mixins: {
            'Magento_Checkout/js/action/set-billing-address': {
                'Sslwireless_Address/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/set-shipping-information': {
                'Sslwireless_Address/js/action/set-shipping-information-mixin': true
            },
            'Magento_Checkout/js/action/create-shipping-address': {
                'Sslwireless_Address/js/action/create-shipping-address-mixin': true
            },
            'Magento_Checkout/js/action/place-order': {
                'Sslwireless_Address/js/action/set-billing-address-mixin': true
            },
            'Magento_Checkout/js/action/create-billing-address': {
                'Sslwireless_Address/js/action/set-billing-address-mixin': true
            },
            'Magento_Customer/js/model/customer/address': {
                'Sslwireless_Address/js/model/customer/address-mixin': true
            }
        }
    },
    map: {
        '*': {
            cityUpdater: 'Sslwireless_Address/js/city-updater',
            townshipUpdater: 'Sslwireless_Address/js/township-updater'
        }
    }
};
