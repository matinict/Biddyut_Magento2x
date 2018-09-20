<?php

namespace Sslwireless\Address\Model\ResourceModel\Township;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Locale township name table name
     *
     * @var string
     */
    protected $townshipNameTable;

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(\Sslwireless\Address\Model\Township::class, \Sslwireless\Address\Model\ResourceModel\Township::class);
        $this->townshipNameTable = $this->getTable('directory_city_township_name');
    }

    /**
     * Get region name by locale
     *
     * @return $this
     */
    public function initLocale($locale)
    {
        $this->addBindParam(':locale', $locale);
        $this->getSelect()->joinLeft(
            ['dctn' => $this->townshipNameTable],
            'main_table.township_id = dctn.township_id AND dctn.locale = :locale',
            ['name']
        );
        $this->addOrder('name', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
        $this->addOrder('default_name', \Magento\Framework\Data\Collection::SORT_ORDER_ASC);
        return $this;
    }

    public function toOptionArray()
    {
        $options = [];
        foreach ($this as $item) {
            $option = [];
            $option['value'] = $item->getTownshipId();
            $option['city_id'] = $item->getCityId();
            $option['title'] = ($item->getName() != '') ? $item->getName() : $item->getDefaultName();
            $option['label'] = ($item->getName() != '') ? $item->getName() : $item->getDefaultName();
            $options[] = $option;
        }

        if (count($options) > 0) {
            array_unshift(
                $options,
                ['title' => null, 'value' => null, 'label' => __('Please select a township.')]
            );
        }
        return $options;
    }

    public function getTownshipData()
    {
        $townships = [];
        foreach ($this as $township) {
            if (!$township->getTownshipId() || !$township->getCityId()) {
                continue;
            }
            $townships[$township->getCityId()][$township->getTownshipId()] = [
                'code' => $township->getCode(),
                'name' => ($township->getName() != '') ? $township->getName() : $township->getDefaultName()
            ];
        }
        return $townships;
    }
}
