<?php

namespace Sslwireless\Address\Controller\Adminhtml\Region;

class Delete extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Sslwireless_Address::region_delete';
    /**
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        // check if we know what should be deleted
        $region_id = $this->getRequest()->getParam('region_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($region_id) {
            $region_name = '';
            try {
                // init model and delete
                $model = $this->regionFactory->create()->load($region_id);
                $region_name = $model->getDefaultName();
                $model->delete();
                $this->messageManager->addSuccess(__('The '.$region_name.' Region has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['region_id' => $region_id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('Region to delete was not found.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
