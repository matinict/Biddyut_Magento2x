<?php

namespace Sslwireless\Address\Plugin\Magento\Framework\Api;

class DataObjectHelper
{
    public function beforePopulateWithArray(
        \Magento\Framework\Api\DataObjectHelper $subject,
        $dataObject,
        array $data,
        $interfaceName
    ) {
        if(ltrim($interfaceName, '\/') == \Magento\Customer\Api\Data\AddressInterface::class){
            $interfaceName = \Sslwireless\Address\Api\Data\AddressInterface::class;
        }
        return [$dataObject, $data, $interfaceName];
    }
}
