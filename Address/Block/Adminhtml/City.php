<?php

namespace Sslwireless\Address\Block\Adminhtml;

/**
 * Adminhtml City content block
 */
class City extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Sslwireless_Address';
        $this->_controller = 'adminhtml_city';
        $this->_headerText = __('City Manager');
        $this->_addButtonLabel = __('Add New City');
        parent::_construct();
    }
}
