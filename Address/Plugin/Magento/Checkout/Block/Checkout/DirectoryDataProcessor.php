<?php

namespace Sslwireless\Address\Plugin\Magento\Checkout\Block\Checkout;

class DirectoryDataProcessor
{
    protected $helper;

    public function __construct(
        \Sslwireless\Address\Helper\Data $helper
    ) {
        $this->helper = $helper;
    }

    public function afterProcess(
        \Magento\Checkout\Block\Checkout\DirectoryDataProcessor $subject,
        $result
    ) {
        $result['components']['checkoutProvider']['dictionaries']['city_id'] = $this->helper->getCityDataProvider();
        $result['components']['checkoutProvider']['dictionaries']['township_id'] = $this->helper->getTownshipDataProvider();
        return $result;
    }
}
