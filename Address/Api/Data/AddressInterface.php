<?php

/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */

namespace Sslwireless\Address\Api\Data;

interface AddressInterface extends \Magento\Customer\Api\Data\AddressInterface
{
    const CITY_ID = 'city_id';
    const TOWNSHIP = 'township';
    const TOWNSHIP_ID = 'township_id';

    /**
     * Get Address City Id
     * @return string
     */
    public function getCityId();

    /**
     * Set Address City Id
     * @param string $township
     * @return $this
     */
    public function setCityId($cityId);

    /**
     * Get Address Township
     * @return string
     */
    public function getTownship();

    /**
     * Set Address Township
     * @param string $township
     * @return $this
     */
    public function setTownship($township);

    /**
     * Get Address Township Id
     * @return int
     */
    public function getTownshipId();

    /**
     * Set Address Township Id
     * @param int $townshipId
     * @return $this
     */
    public function setTownshipId($townshipId);
}
