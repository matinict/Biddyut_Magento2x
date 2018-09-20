<?php

namespace Sslwireless\Address\Block\Adminhtml\Importaddress;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Initialize form
     * Add standard buttons
     * Add "Save and Continue" button
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_importaddress';
        $this->_blockGroup = 'Sslwireless_Address';

        parent::_construct();
        $this->buttonList->update('save', 'label', __('Import'));
        $this->buttonList->remove('back');
    }

    /**
     * Getter for form header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
        return __('Import Address');
    }
}
