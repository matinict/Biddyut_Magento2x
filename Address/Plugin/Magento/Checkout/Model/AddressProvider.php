<?php

namespace Sslwireless\Address\Plugin\Magento\Checkout\Model;

class AddressProvider
{
    protected $customerSession;
    protected $addressFactory;
    protected $helperData;

    public function __construct(
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Sslwireless\Address\Helper\Data $helperData
    ) {
        $this->customerSession = $customerSession;
        $this->addressFactory = $addressFactory;
        $this->helperData = $helperData;
    }

    public function afterGetConfig(
        \Magento\Checkout\Model\DefaultConfigProvider $subject,
        $result
    ) {
        if($this->customerSession->isLoggedIn()){
            $additionalFields = $this->helperData->getExtraCheckoutAddressFields();
            foreach ($result['customerData']['addresses'] as $key => $address) {
                $addressData = $this->addressFactory->create()->load($address['id']);
                foreach ($additionalFields as $field) {
                    $result['customerData']['addresses'][$key][$field] = $addressData->getData($field);
                }
            }
        }
        return $result;
    }
}
