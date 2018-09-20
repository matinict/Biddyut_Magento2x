<?php

namespace Sslwireless\Address\Model\ResourceModel\Region;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    /**
     * Define resource model.
     */
    protected function _construct()
    {
        $this->_init(\Sslwireless\Address\Model\Region::class, \Sslwireless\Address\Model\ResourceModel\Region::class);
    }

    public function toOptionArray()
    {
        $options = [];
        foreach ($this as $item) {
            $option = [];
            $option['value'] = $item->getRegionId();
            $option['label'] = $item->getCountryId() . ' - ' . $item->getDefaultName();
            $options[] = $option;
        }

        if (count($options) > 0) {
            array_unshift(
                $options,
                ['title' => null, 'value' => null, 'label' => __('Please select a region.')]
            );
        }
        return $options;
    }
}
