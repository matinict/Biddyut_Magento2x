<?php
/**
 * Refer to LICENSE.txt distributed with the Temando Shipping module for notice of license
 */
namespace Temando\Shipping\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Temando\Shipping\Api\Data\Checkout\AddressInterface;
use Temando\Shipping\Api\Data\Checkout\AddressInterfaceFactory;
use Temando\Shipping\Model\ResourceModel\Repository\AddressRepositoryInterface;

/**
 * Save checkout fields with quote shipping address.
 *
 * @package  Temando\Shipping\Observer
 * @author   Christoph Aßmann <christoph.assmann@netresearch.de>
 * @license  http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link     http://www.temando.com/
 */
class SaveCheckoutFieldsObserverBefore implements ObserverInterface
{
    /**
     * @var AddressRepositoryInterface
     */
    private $addressRepository;

    /**
     * @var AddressInterfaceFactory
     */
    private $addressFactory;

    /**
     * SaveCheckoutFieldsObserver constructor.
     * @param AddressRepositoryInterface $addressRepository
     * @param AddressInterfaceFactory $addressFactory
     */
    public function __construct(
        AddressRepositoryInterface $addressRepository,
        AddressInterfaceFactory $addressFactory
    ) {
        $this->addressRepository = $addressRepository;
        $this->addressFactory = $addressFactory;
    }

    /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
      if($address = $observer->getEvent()->getQuoteAddress()){
            if($attributes = $address->getExtensionAttributes()){
                $checkoutFields = $attributes->getCheckoutFields();
                if(is_null($checkoutFields)){
                    $attributes->setCheckoutFields(array());
                    }
                }
            }
    //     /** @var \Magento\Quote\Api\Data\AddressInterface|\Magento\Quote\Model\Quote\Address $quoteAddress */
    //     $quoteAddress = $observer->getData('quote_address');
    //     if ($quoteAddress->getAddressType() !== \Magento\Quote\Model\Quote\Address::ADDRESS_TYPE_SHIPPING) {
    //         return;
    //     }
    //
    //     if (!$quoteAddress->getExtensionAttributes()) {
    //         return;
    //     }
    //
    //     // persist checkout fields
    //     try {
    //         $checkoutAddress = $this->addressRepository->getByQuoteAddressId($quoteAddress->getId());
    //     } catch (NoSuchEntityException $e) {
    //         $checkoutAddress = $this->addressFactory->create(['data' => [
    //             AddressInterface::SHIPPING_ADDRESS_ID => $quoteAddress->getId(),
    //         ]]);
    //     }
    //
    //     $extensionAttributes = $quoteAddress->getExtensionAttributes();
    //     $checkoutAddress->setServiceSelection($extensionAttributes->getCheckoutFields());
    //     $this->addressRepository->save($checkoutAddress);
    }
}
