<?php
/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */
namespace Sslwireless\Address\Model;

class Region extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Cache tag
     *
     * @var string
     */
    const CACHE_TAG = 'directory_country_region';
    /**
     * Initialize resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Sslwireless\Address\Model\ResourceModel\Region');
    }
}
