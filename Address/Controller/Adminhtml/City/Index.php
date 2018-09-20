<?php

namespace Sslwireless\Address\Controller\Adminhtml\City;

class Index extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb('Cities Manager','Cities Manager');
        $resultPage->getConfig()->getTitle()->prepend(__('Customers'));
        $resultPage->getConfig()->getTitle()->prepend('Cities Manager');
        return $resultPage;
    }
}
