<?php

namespace Sslwireless\Address\Plugin\Magento\Customer\Block\Address;

class Edit
{
    public function beforeSetTemplate(
        \Magento\Customer\Block\Address\Edit $subject,
        $template
    ) {
        return ['Sslwireless_Address::address/edit.phtml'];
    }
}
