<?php

namespace Sslwireless\Address\Plugin\Magento\Quote\Model;

class ShippingAddressManagement
{
    protected $helper;

    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Sslwireless\Address\Helper\Data $helper
    ) {
        $this->logger = $logger;
        $this->helper = $helper;
    }

    public function beforeAssign(
        \Magento\Quote\Model\ShippingAddressManagement $subject,
        $cartId,
        \Magento\Quote\Api\Data\AddressInterface $address
    ) {
        $extAttributes = $address->getExtensionAttributes();
        if (!empty($extAttributes)) {
            $this->helper->transportFieldsFromExtensionAttributesToObject(
                $extAttributes,
                $address,
                'extra_checkout_shipping_address_fields'
            );
        }
    }
}
