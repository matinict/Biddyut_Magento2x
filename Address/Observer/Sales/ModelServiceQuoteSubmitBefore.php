<?php
/**
 * Sslwireless Address(Customer Information For Logistics & delivery Solution Bangladesh) Magento 2.x
 *
 * @Package    Sslwireless Address
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 */

namespace Sslwireless\Address\Observer\Sales;

class ModelServiceQuoteSubmitBefore implements \Magento\Framework\Event\ObserverInterface
{
    protected $helper;
    protected $logger;
    protected $quoteRepository;

    public function __construct(
        \Magento\Quote\Model\QuoteRepository $quoteRepository,
        \Psr\Log\LoggerInterface $logger,
        \Sslwireless\Address\Helper\Data $helper
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->logger = $logger;
        $this->helper = $helper;
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
        /** @var \Magento\Sales\Model\Order $order */
        $order = $observer->getOrder();
        $quote = $this->quoteRepository->get($order->getQuoteId());

        $this->helper->transportFieldsFromExtensionAttributesToObject(
            $quote->getBillingAddress(),
            $order->getBillingAddress(),
            'extra_checkout_billing_address_fields'
        );

        $this->helper->transportFieldsFromExtensionAttributesToObject(
            $quote->getShippingAddress(),
            $order->getShippingAddress(),
            'extra_checkout_shipping_address_fields'
        );
    }
}
