<?php

namespace Sslwireless\Address\Model\ResourceModel\Address\Attribute\Source;

class Township extends \Magento\Eav\Model\Entity\Attribute\Source\Table
{
    /**
     * @var \Sslwireless\Address\Model\ResourceModel\Township\CollectionFactory
     */
    protected $townshipFactory;

    /**
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory
     * @param \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory $attrOptionFactory
     * @param \Magento\Framework\Locale\ResolverInterface $locale
     * @param \Sslwireless\Address\Model\ResourceModel\Township\CollectionFactory $townshipFactory
     */
    public function __construct(
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory,
        \Magento\Eav\Model\ResourceModel\Entity\Attribute\OptionFactory $attrOptionFactory,
        \Magento\Framework\Locale\ResolverInterface $locale,
        \Sslwireless\Address\Model\ResourceModel\Township\CollectionFactory $townshipFactory
    ) {
        $this->locale = $locale;
        $this->townshipFactory = $townshipFactory;
        parent::__construct($attrOptionCollectionFactory, $attrOptionFactory);
    }

    /**
     * Retrieve all region options
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = $this->_createCollection()->toOptionArray();
        }
        return $this->_options;
    }

    /**
     * @return \Sslwireless\Checkout\Model\ResourceModel\Township\Collection
     */
    protected function _createCollection()
    {
        return $this->townshipFactory->create()->load()->initLocale($this->locale);
    }
}
