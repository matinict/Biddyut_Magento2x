<?php

namespace Sslwireless\Address\Block\Adminhtml\Township\Edit;

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
        $this->setId('township_form');
        $this->setTitle(__('Township Information'));
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

        $city_list = $this->_coreRegistry->registry('sslwireless_address_city_list');
        $fieldset->addField(
            'city_id',
            'select',
            [
                'name' => 'city_id',
                'label' => __('City'),
                'title' => __('City'),
                'required' => true,
                'values' => $city_list
            ]
        );

        $fieldset->addField(
            'code',
            'text',
            [
                'name' => 'code',
                'label' => __('Township Code'),
                'title' => __('Township Code'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'default_name',
            'text',
            [
                'name' => 'default_name',
                'label' => __('Township/Zone Name'),
                'title' => __('Township/Zone Name'),
                'required' => true
            ]
        );

        $fieldset->addField(
            'postcode',
            'textarea',
            [
                'name' => 'postcode',
                'label' => __('Postcode'),
                'title' => __('Postcode'),
                'required' => true,
                'note' => 'Separate postcode by comma (,)'
            ]
        );

        $formData = $this->_coreRegistry->registry('sslwireless_address_township');
        if ($formData) {
            if ($formData->getRegionId()) {
                $fieldset->addField(
                    'township_id',
                    'hidden',
                    ['name' => 'township_id']
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
