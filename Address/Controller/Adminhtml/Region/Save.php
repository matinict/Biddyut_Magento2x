<?php

namespace Sslwireless\Address\Controller\Adminhtml\Region;

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
            $region_id = $this->getRequest()->getParam('region_id');
            /** @var \Sslwireless\Address\Model\Region $model */
            $model = $this->regionFactory->create()->load($region_id);
            if (!$model->getRegionId() && $region_id) {
                $this->messageManager->addError(__('This region no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccess(__('You saved the region.'));

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['region_id' => $model->getRegionId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addException($e, __('Something went wrong while saving the region.'));
            }

            $this->_getSession()->setFormData($data);
            if ($this->getRequest()->getParam('region_id')) {
                return $resultRedirect->setPath('*/*/edit', ['region_id' => $region_id]);
            }
            return $resultRedirect->setPath('*/*/new');
        }
        return $resultRedirect->setPath('*/*/');
    }
}
