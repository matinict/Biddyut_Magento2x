<?php

namespace Sslwireless\Address\Plugin\Magento\Customer\Model\Address;

class Mapper
{
    protected $addressFactory;
    protected $helperData;

    public function __construct(
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Sslwireless\Address\Helper\Data $helperData
    ) {
        $this->addressFactory = $addressFactory;
        $this->helperData = $helperData;
    }

    public function afterToFlatArray(
        \Magento\Customer\Model\Address\Mapper $subject,
        $result
    ) {
        if($result['id']){
            $addressData = $this->addressFactory->create()->load($result['id']);
            $additionalFields = $this->helperData->getExtraCheckoutAddressFields();
            foreach ($additionalFields as $field) {
                $result[$field] = $addressData->getData($field);
            }
        }
        return $result;
    }
}
