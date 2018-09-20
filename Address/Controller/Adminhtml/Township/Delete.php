<?php

namespace Sslwireless\Address\Controller\Adminhtml\Township;

class Delete extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Sslwireless_Address::township_delete';

    /**
     *
     * @return \Magento\Framework\View\Result\PageFactory
     */
    public function execute()
    {
        // check if we know what should be deleted
        $township_id = $this->getRequest()->getParam('township_id');
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        if ($township_id) {
            $township_name = '';
            try {
                // init model and delete
                $model = $this->townshipFactory->create()->load($township_id);
                $township_name = $model->getDefaultName();
                $model->delete();
                $this->messageManager->addSuccess(__('The '.$township_name.' has been deleted.'));
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addError($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['township_id' => $township_id]);
            }
        }
        // display error message
        $this->messageManager->addError(__('Township to delete was not found.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
