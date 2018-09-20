<?php

namespace Sslwireless\Address\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class City extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('directory_region_city', 'city_id');
    }

    /**
     * Perform operations before object save
     *
     * @param AbstractModel $object
     * @return $this
     * @throws LocalizedException
     */
    protected function _beforeSave(AbstractModel $object)
    {
        if (!$this->isCityExist($object)) {
            throw new LocalizedException(__('The city already exists.'));
        }
    }

    protected function isCityExist(AbstractModel $object)
    {
        if (!$this->checkCityAlreadyExist($object, 'default_name') || !$this->checkCityAlreadyExist($object, 'code')) {
            return false;
        }
        return true;
    }

    protected function checkCityAlreadyExist(AbstractModel $object, $field)
    {
        $select = $this->getConnection()->select()
            ->from(['drc' => $this->getMainTable()])
            ->where('drc.region_id = ?', $object->getData('region_id'))
            ->where('drc.'.$field.' = ?', $object->getData($field));
        if ($object->getData('city_id')) {
            $select->where('drc.city_id != ?', $object->getData('city_id'));
        }
        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }
        return true;
    }
}
