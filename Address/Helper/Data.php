<?php
/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */
namespace Sslwireless\Address\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    protected $logger;
    protected $fieldsetConfig;
    protected $storeManager;
    protected $configCacheType;
    protected $jsonHelper;
    protected $addressFactory;
    protected $locale;
    protected $cityCollection;
    protected $cityJson;
    protected $townshipCollection;
    protected $townshipJson;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\DataObject\Copy\Config $fieldsetConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Cache\Type\Config $configCacheType,
        \Magento\Framework\Json\Helper\Data $jsonHelper,
        \Magento\Customer\Model\AddressFactory $addressFactory,
        \Magento\Framework\Locale\ResolverInterface $locale,
        \Sslwireless\Address\Model\ResourceModel\City\CollectionFactory $cityCollection,
        \Sslwireless\Address\Model\ResourceModel\Township\CollectionFactory $townshipCollection
    ) {
        $this->logger = $logger;
        $this->fieldsetConfig = $fieldsetConfig;
        $this->storeManager = $storeManager;
        $this->configCacheType = $configCacheType;
        $this->jsonHelper = $jsonHelper;
        $this->addressFactory = $addressFactory;
        $this->locale = $locale;
        $this->cityCollection = $cityCollection;
        $this->townshipCollection = $townshipCollection;
    }

    public function getExtraCheckoutAddressFields(
        $fieldset='extra_checkout_billing_address_fields',
        $root='global'
    ){
        $fields = $this->fieldsetConfig->getFieldset($fieldset, $root);
        $extraCheckoutFields = [];
        foreach($fields as $field => $fieldInfo){
            $extraCheckoutFields[] = $field;
        }
        return $extraCheckoutFields;
    }

    public function transportFieldsFromExtensionAttributesToObject(
        $fromObject,
        $toObject,
        $fieldset='extra_checkout_billing_address_fields'
    ) {
        foreach($this->getExtraCheckoutAddressFields($fieldset) as $extraField) {
            $set = 'set' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));
            $get = 'get' . str_replace(' ', '', ucwords(str_replace('_', ' ', $extraField)));
            $value = $fromObject->$get();
            try {
                $toObject->$set($value);
            } catch (\Exception $e) {
                $this->logger->critical($e->getMessage());
            }
        }
        return $toObject;
    }

    public function getCityDataProvider()
    {
        return $this->cityCollection->create()->load()->initLocale($this->locale)->toOptionArray();
    }

    /**
     * Retrieve city data json
     *
     * @return string
     */
    public function getCityJson()
    {
        if (!$this->cityJson) {
            $cacheKey = 'DIRECTORY_CITY_JSON_STORE' . $this->storeManager->getStore()->getId();
            $json = $this->configCacheType->load($cacheKey);
            if (empty($json)) {
                $cities = $this->cityCollection->create()->load()->initLocale($this->locale)->getCityData();
                $json = $this->jsonHelper->jsonEncode($cities);
                if ($json === false) {
                    $json = 'false';
                }
                $this->configCacheType->save($json, $cacheKey);
            }
            $this->cityJson = $json;
        }
        return $this->cityJson;
    }

    public function getTownshipDataProvider()
    {
        return $this->townshipCollection->create()->load()->initLocale($this->locale)->toOptionArray();
    }

    /**
     * Retrieve township data json
     *
     * @return string
     */
    public function getTownshipJson()
    {
        if (!$this->townshipJson) {
            $cacheKey = 'DIRECTORY_TOWNSHIP_JSON_STORE' . $this->storeManager->getStore()->getId();
            $json = $this->configCacheType->load($cacheKey);
            if (empty($json)) {
                $townships = $this->townshipCollection->create()->load()->initLocale($this->locale)->getTownshipData();
                $json = $this->jsonHelper->jsonEncode($townships);
                if ($json === false) {
                    $json = 'false';
                }
                $this->configCacheType->save($json, $cacheKey);
            }
            $this->townshipJson = $json;
        }
        return $this->townshipJson;
    }

    public function getAddressData($addressId, $field)
    {
        $addressData = $this->addressFactory->create()->load($addressId);
        if($addressData->getId()){
            return $addressData->getData($field);
        }
        return false;
    }
}
