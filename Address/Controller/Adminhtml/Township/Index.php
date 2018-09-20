<?php

namespace Sslwireless\Address\Controller\Adminhtml\Township;

class Index extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb('Township Manager','Township Manager');
        $resultPage->getConfig()->getTitle()->prepend(__('Customers'));
        $resultPage->getConfig()->getTitle()->prepend('Township Manager');
        return $resultPage;
    }
}
