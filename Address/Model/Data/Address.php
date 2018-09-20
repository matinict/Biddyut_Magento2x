<?php
/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */
 
namespace Sslwireless\Address\Model\Data;

class Address extends \Magento\Customer\Model\Data\Address implements \Sslwireless\Address\Api\Data\AddressInterface
{
    /**
     * Get city id
     *
     * @return string
     */
    public function getCityId()
    {
        return $this->_get(self::CITY_ID);
    }

    /**
     * Set city id
     *
     * @return $this
     */
    public function setCityId($cityId)
    {
        return $this->setData(self::CITY_ID, $cityId);
    }

    /**
     * Get township
     *
     * @return string
     */
    public function getTownship()
    {
        return $this->_get(self::TOWNSHIP);
    }

    /**
     * Set township
     *
     * @return $this
     */
    public function setTownship($township)
    {
        return $this->setData(self::TOWNSHIP, $township);
    }

    /**
     * Get id
     *
     * @return int|null
     */
    public function getTownshipId()
    {
        return $this->_get(self::TOWNSHIP_ID);
    }

    /**
     * Set township_id
     *
     * @return $this
     */
    public function setTownshipId($townshipId)
    {
        return $this->setData(self::TOWNSHIP_ID, $townshipId);
    }
}
