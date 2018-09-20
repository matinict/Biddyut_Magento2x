<?php

namespace Sslwireless\Address\Model\ResourceModel;

class Address extends \Magento\Customer\Model\ResourceModel\Address
{
    public function updateAddressData($addressId, $addressData)
    {
        $update = [];
        if(array_key_exists('city_id', $addressData)){
            $update['city_id'] = $addressData['city_id'];
        }
        if(array_key_exists('township_id', $addressData)){
            $update['township_id'] = $addressData['township_id'];
        }
        if(array_key_exists('township', $addressData)){
            $update['township'] = $addressData['township'];
        }
        if(count($update) > 0){
            $where = ['entity_id = ?' => $addressId];
            $this->getConnection()->update($this->getEntityTable(), $update, $where);
        }
    }
}
