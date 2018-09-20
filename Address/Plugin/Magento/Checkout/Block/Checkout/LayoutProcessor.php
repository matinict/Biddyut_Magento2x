<?php

namespace Sslwireless\Address\Plugin\Magento\Checkout\Block\Checkout;

class LayoutProcessor
{
    protected $helper;

    public function __construct(
        \Sslwireless\Address\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    /**
     * @param \Magento\Checkout\Block\Checkout\LayoutProcessor $subject
     * @param array $jsLayout
     * @return array
     */
    public function afterProcess(
        \Magento\Checkout\Block\Checkout\LayoutProcessor $subject,
        array $result
    ) {
        $result = $this->getShippingFormFields($result);
        $result = $this->getBillingFormFields($result);
        return $result;
    }

    protected function getShippingFormFields($result){
        if(isset($result['components']['checkout']['children']['steps']['children']['shipping-step']['children']['shippingAddress']['children']['shipping-address-fieldset'])
        ){
            $shippingCustomFields = $this->getFields('shippingAddress.custom_attributes','shipping');

            $shippingFields = $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'];
            if(isset($shippingFields['street'])){
                unset($shippingFields['street']['children'][1]['validation']);
                unset($shippingFields['street']['children'][2]['validation']);
            }
            if(isset($shippingFields['township'])){
                unset($shippingFields['township']);
            }
            $shippingFields = array_replace_recursive($shippingFields, $shippingCustomFields);

            $result['components']['checkout']['children']['steps']['children']
            ['shipping-step']['children']['shippingAddress']['children']
            ['shipping-address-fieldset']['children'] = $shippingFields;
        }


        $result['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['township_id']['label'] = __('Zone');

        //['children']['township']['label'] = __('TEST');

        $result['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['country_id']['sortOrder'] = 71;

        $result['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']
        ['children']['city']['sortOrder'] = 108;



        return $result;
    }

    protected function getBillingFormFields($result){
        if(isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list'])) {
            $paymentForms = $result['components']['checkout']['children']['steps']['children']
            ['billing-step']['children']['payment']['children']
            ['payments-list']['children'];
            foreach ($paymentForms as $paymentMethodForm => $paymentMethodValue) {
                $paymentMethodCode = str_replace('-form', '', $paymentMethodForm);
                if (!isset($result['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'][$paymentMethodCode . '-form'])) {
                    continue;
                }
                $billingFields = $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'];
                $billingPostcodeFields = $this->getFields('billingAddress' . $paymentMethodCode . '.custom_attributes','billing');
                $billingFields = array_replace_recursive($billingFields, $billingPostcodeFields);

                if(isset($billingFields['township'])){
                    unset($billingFields['township']);
                }

                 $billingFields['township_id']['label'] ="Zone";
                $billingFields['country_id']['sortOrder'] = 71;
                $billingFields['city']['sortOrder'] = 108;

                $result['components']['checkout']['children']['steps']['children']
                ['billing-step']['children']['payment']['children']
                ['payments-list']['children'][$paymentMethodCode . '-form']['children']['form-fields']['children'] = $billingFields;
            }
        }

        return $result;
    }

    protected function getFields($scope,$addressType){
        $fields = [];
        foreach($this->getAdditionalFields($addressType) as $field){
            $fields[$field] = $this->getField($field,$scope);
        }
        return $fields;
    }

    protected function getAdditionalFields($addressType='shipping'){
        if($addressType=='shipping') {
            return $this->helper->getExtraCheckoutAddressFields('extra_checkout_shipping_address_fields');
        }
        return  $this->helper->getExtraCheckoutAddressFields('extra_checkout_billing_address_fields');
    }

    protected function getField($attributeCode,$scope) {
        $field = [];
        if ($attributeCode == 'city_id') {
            $field = [
                'component' => 'Sslwireless_Address/js/form/element/city',
                'config' => [
                    'customScope' => $scope,
                    // 'customEntry' => 'shippingAddress.custom_attributes.city',
                    'elementTmpl' => 'ui/form/element/select',
                ],
                'validation' => [
                    'required-entry' => true,
                ],
                'filterBy' => [
                    'target' => 'checkoutProvider:shippingAddress.region_id',
                    'field' => 'region_id',
                ],
                'imports' => [
                    'initialOptions' => 'index = checkoutProvider:dictionaries.city_id',
                    'setOptions' => 'index = checkoutProvider:dictionaries.city_id',
                ],
                'deps' => 'checkoutProvider',
                'dataScope' => $scope . '.' . $attributeCode,
            ];
        } elseif ($attributeCode == 'township_id') {
            $field = [
                'component' => 'Sslwireless_Address/js/form/element/township',
                'config' => [
                    'customScope' => $scope,
                    'customEntry' => 'shippingAddress.custom_attributes.township',
                    'elementTmpl' => 'ui/form/element/select',
                ],
                'validation' => [
                    'required-entry' => true,
                ],
                'filterBy' => [
                    'target' => 'checkoutProvider:shippingAddress.city_id',
                    'field' => 'city_id',
                ],
                'imports' => [
                    'initialOptions' => 'index = checkoutProvider:dictionaries.township_id',
                    'setOptions' => 'index = checkoutProvider:dictionaries.township_id',
                ],
                'deps' => 'checkoutProvider',
                'dataScope' => $scope . '.' . $attributeCode,
            ];
        } elseif ($attributeCode == 'township') {
            $field = [
                'config' => [
                    'customScope' => $scope,
                ],
                'validation' => [
                    'required-entry' => true,
                ],
                'dataScope' => $scope . '.' . $attributeCode,
            ];
        }
        return $field;
    }
}
