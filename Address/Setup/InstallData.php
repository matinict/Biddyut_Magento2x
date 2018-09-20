<?php
/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */
namespace Sslwireless\Address\Setup;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Config;

/**
 * Class InstallData
 * @package     Devchannel\CustomAttribute\Setup
 */
class InstallData implements InstallDataInterface
{
    /**
     * @var EavSetup
     */
    private $eavSetup;

    /**
     * @var Config
     */
    private $eavConfig;

    /**
     * InstallData constructor.
     * @param EavSetup $eavSetup
     * @param Config $config
     */
    public function __construct(
        EavSetup $eavSetup,
        Config $config
    ) {
        $this->eavSetup = $eavSetup;
        $this->eavConfig = $config;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        $this->eavSetup->addAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'city_id',
            [
                'type' => 'static',
                'label' => 'City',
                'input' => 'hidden',
                'required' => false,
                'visible' => true,
                'user_defined' => false,
                'sort_order' => 107,
                'position' => 107,
                'system' => 1,
                'source' => 'Sslwireless\Address\Model\ResourceModel\Address\Attribute\Source\City'
            ]
        );
        $city_id = $this->eavConfig->getAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'city_id'
        );
        $city_id->setData(
            'used_in_forms',
            ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
        );
        $city_id->save();

        $city = $this->eavConfig->getAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'city'
        );
        $city->setData('sort_order', 108);
        $city->save();

        $this->eavSetup->addAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'township_id',
            [
                'type' => 'static',
                'label' => 'Township',
                'input' => 'hidden',
                'required' => false,
                'visible' => true,
                'user_defined' => false,
                'sort_order' => 109,
                'position' => 109,
                'system' => 1,
                'source' => 'Sslwireless\Address\Model\ResourceModel\Address\Attribute\Source\Township'
            ]
        );
        $township_id = $this->eavConfig->getAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'township_id'
        );
        $township_id->setData(
            'used_in_forms',
            ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
        );
        $township_id->save();

        $this->eavSetup->addAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'township',
            [
                'type' => 'static',
                'label' => 'Township',
                'input' => 'text',
                'required' => false,
                'visible' => true,
                'user_defined' => false,
                'sort_order' => 110,
                'position' => 110,
                'system' => 1
            ]
        );
        $township = $this->eavConfig->getAttribute(
            AddressMetadataInterface::ENTITY_TYPE_ADDRESS,
            'township'
        );
        $township->setData(
            'used_in_forms',
            ['adminhtml_customer_address', 'customer_address_edit', 'customer_register_address']
        );
        $township->save();

        $setup->endSetup();
    }
}
