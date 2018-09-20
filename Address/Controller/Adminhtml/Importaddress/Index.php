<?php
/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */
namespace Sslwireless\Address\Controller\Adminhtml\Importaddress;

class Index extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Sslwireless_Address::import_address';

    public function execute()
    {
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb('Import Address','Import Address');
        $resultPage->getConfig()->getTitle()->prepend(__('Customers'));
        $resultPage->getConfig()->getTitle()->prepend('Import Address');
        return $resultPage;
    }
}
