<?php

namespace Sslwireless\Address\Controller\Adminhtml\Region;

class Edit extends \Sslwireless\Address\Controller\Adminhtml\Address
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Sslwireless_Address::region_edit';

    /**
     * Edit Region page
     *
     * @return \Magento\Backend\Model\View\Result\Page|\Magento\Backend\Model\View\Result\Redirect
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function execute()
    {
        // Get ID and create model
        $region_id = (int) $this->getRequest()->getParam('region_id');
        $model = $this->regionFactory->create();
        $model->setData([]);
        // Initial checking
        if ($region_id && $region_id > 0) {
            $model->load($region_id);
            if (!$model->getRegionId()) {
                $this->messageManager->addError(__('This region no longer exists.'));
                /** \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
                $resultRedirect = $this->resultRedirectFactory->create();
                return $resultRedirect->setPath('*/*/');
            }
            $default_name = $model->getDefaultName();
        }

        $formData = $this->sessionFactory->create()->getFormData(true);
        if (!empty($formData)) {
            $model->setData($formData);
        }

        $this->coreRegistry->register('sslwireless_address_region', $model);
        $this->coreRegistry->register('sslwireless_address_country_list', $this->sourceCountry->toOptionArray());

        // Build edit form
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->_initAction();
        $resultPage->addBreadcrumb(
            $region_id ? __('Edit Region') : __('New Region'),
            $region_id ? __('Edit Region') : __('New Region')
        );
        $resultPage->getConfig()->getTitle()->prepend(__('Regions Manager'));
        $resultPage->getConfig()->getTitle()->prepend($region_id ? 'Edit: '.$default_name.' ('.$region_id.')' : __('New Region'));

        return $resultPage;
    }
}
