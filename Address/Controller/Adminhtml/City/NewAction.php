<?php

namespace Sslwireless\Address\Controller\Adminhtml\City;

class NewAction extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * Create new Region
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $this->_forward('edit');
    }
}
