<?php

namespace Sslwireless\Address\Controller\Adminhtml\Township;

use Magento\Framework\Exception\LocalizedException;
use Magento\TestFramework\Inspection\Exception;

class Save extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $township_id = $this->getRequest()->getParam('township_id');
            /** @var \Sslwireless\Address\Model\Township $model */
            $model = $this->townshipFactory->create()->load($township_id);
            if (!$model->getTownshipId() && $township_id) {
                $this->messageManager->addError(__('This township no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the township.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['township_id' => $model->getTownshipId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the township.'));
            }

            $this->_getSession()->setFormData($data);
            if ($this->getRequest()->getParam('township_id')) {
                return $resultRedirect->setPath('*/*/edit', ['township_id' => $township_id]);
            }
            return $resultRedirect->setPath('*/*/new');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
