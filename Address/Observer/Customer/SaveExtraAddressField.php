<?php
/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */

namespace Sslwireless\Address\Observer\Customer;

class SaveExtraAddressField implements \Magento\Framework\Event\ObserverInterface
{
    protected $cityFactory;
    protected $townshipFactory;
    protected $addressFactory;

    public function __construct(
        \Sslwireless\Address\Model\CityFactory $cityFactory,
        \Sslwireless\Address\Model\TownshipFactory $townshipFactory,
        \Sslwireless\Address\Model\ResourceModel\AddressFactory $addressFactory
    ) {
        $this->cityFactory = $cityFactory;
        $this->townshipFactory = $townshipFactory;
        $this->addressFactory = $addressFactory;
    }

    /**
     * Execute observer
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     */
    public function execute(
        \Magento\Framework\Event\Observer $observer
    ) {
        $request = $observer->getEvent()->getRequest();
        $addresses = $request->getParam('address');
        foreach ($addresses as $address) {
            if(array_key_exists('entity_id', $address)){
                if(array_key_exists('city_id', $address)){
                    $city = $this->cityFactory->create()->load($address['city_id']);
                    $address['city'] = $city->getDefaultName();
                }
                if(array_key_exists('township_id', $address)){
                    $township = $this->townshipFactory->create()->load($address['township_id']);
                    $address['township'] = $township->getDefaultName();
                }
                $this->addressFactory->create()->updateAddressData($address['entity_id'], $address);
            }
        }
    }
}
