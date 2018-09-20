<?php

namespace Sslwireless\Address\Model\ResourceModel;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;

class Region extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    /**
     * construct
     * @return void
     */
    protected function _construct()
    {
        $this->_init('directory_country_region', 'region_id');
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
        if (!$this->isRegionExist($object)) {
            throw new LocalizedException(__('The region already exists.'));
        }
    }

    protected function isRegionExist(AbstractModel $object)
    {
        if (!$this->checkRegionAlreadyExist($object, 'default_name') || !$this->checkRegionAlreadyExist($object, 'code')) {
            return false;
        }
        return true;
    }

    protected function checkRegionAlreadyExist(AbstractModel $object, $field)
    {
        $select = $this->getConnection()->select()
            ->from(['dcr' => $this->getMainTable()])
            ->where('dcr.country_id = ?', $object->getData('country_id'))
            ->where('dcr.'.$field.' = ?', $object->getData($field));
        if ($object->getData('region_id')) {
            $select->where('dcr.region_id != ?', $object->getData('region_id'));
        }
        if ($this->getConnection()->fetchRow($select)) {
            return false;
        }
        return true;
    }
}
