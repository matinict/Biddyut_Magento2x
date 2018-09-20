<?php

namespace Sslwireless\Address\Block\Adminhtml\City\Edit;

use \Magento\Backend\Block\Widget\Form\Generic;

class Form extends Generic
{
    protected $_coreRegistry = null;
    /**
     * @param \Magento\Backend\Block\Template\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Data\FormFactory $formFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Data\FormFactory $formFactory,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $registry, $formFactory, $data);
    }

    /**
     * Init form
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setId('city_form');
        $this->setTitle(__('City Information'));
    }

    /**
     * Prepare form
     *
     * @return $this
     */
    protected function _prepareForm()
    {
        $form = $this->_formFactory->create(
            [
                'data' => [
                    'id' => 'edit_form',
                    'action' => $this->getData('action'),
                    'method' => 'post'
                ]
            ]
        );
        $form->setHtmlIdPrefix('city_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => __('General Information'),
                'class' => 'fieldset-wide'
            ]
        );

        $region_list = $this->_coreRegistry->registry('sslwireless_address_region_list');
        $fieldset->addField(
            'region_id',
            'select',
            [
                'name' => 'region_id',
                'label' => __('State/Region'),
                'title' => __('State/Region'),
                'required' => true,
                'values' => $region_list
            ]
        );

        $fieldset->addField(
            'code',
            'text',
            [
                'name' => 'code',
                'label' => __('City Code'),
                'title' => __('City Code'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'default_name',
            'text',
            [
                'name' => 'default_name',
                'label' => __('City Name'),
                'title' => __('City Name'),
                'required' => true
            ]
        );

        $formData = $this->_coreRegistry->registry('sslwireless_address_city');
        if ($formData) {
            if ($formData->getRegionId()) {
                $fieldset->addField(
                    'city_id',
                    'hidden',
                    ['name' => 'city_id']
                );
            }
            $form->setValues($formData->getData());
        }

        $form->setUseContainer(true);
        $form->setMethod('post');
        $this->setForm($form);

        return parent::_prepareForm();
    }
}
