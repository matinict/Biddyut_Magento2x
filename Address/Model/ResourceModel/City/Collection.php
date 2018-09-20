<?php

namespace Sslwireless\Address\Model\ResourceModel\City;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Locale region name table name
     *
     * @var string
     */
    protected $cityNameTable;

    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(\Sslwireless\Address\Model\City::class, \Sslwireless\Address\Model\ResourceModel\City::class);
        $this->cityNameTable = $this->getTable('directory_region_city_name');
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
            ['drc' => $this->cityNameTable],
            'main_table.city_id = drc.city_id AND drc.locale = :locale',
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
            $option['value'] = $item->getCityId();
            $option['region_id'] = $item->getRegionId();
            $option['title'] = ($item->getName() != '') ? $item->getName() : $item->getDefaultName();
            $option['label'] = ($item->getName() != '') ? $item->getName() : $item->getDefaultName();
            $options[] = $option;
        }

        if (count($options) > 0) {
            array_unshift(
                $options,
                ['title' => null, 'value' => null, 'label' => __('Please select a city.')]
            );
        }
        return $options;
    }

    public function getCityData()
    {
        $cities = [];
        foreach ($this as $city) {
            if (!$city->getCityId() || !$city->getRegionId()) {
                continue;
            }
            $cities[$city->getRegionId()][$city->getCityId()] = [
                'code' => $city->getCode(),
                'name' => ($city->getName() != '') ? $city->getName() : $city->getDefaultName()
            ];
        }
        return $cities;
    }
}
