<?php
/**
 * Biddyut(A technology driven Logistics & delivery company Bangladesh) Magento 2.x
 *
 * @Package    Biddyut Limited
 * @Developer  Abdul Matin <matinict@gmail.com>
 * @Author     Sslwireless(https://github.com/sslcommerz)
 * @Dependency    SSLWireless Address(https://github.com/sslcommerz)
 */

namespace Sslwireless\Biddyut\Model\Carrier;

use Sslwireless\Biddyut\Model\Query\Api as ApiData;
use Magento\Quote\Model\Quote\Address\RateRequest;
use Magento\Checkout\Model\Session as CartSession;
use Magento\Checkout\Model\Cart as CustomerCart;

class Biddyut extends \Magento\Shipping\Model\Carrier\AbstractCarrier implements
    \Magento\Shipping\Model\Carrier\CarrierInterface {
    /**
     * @var string
     */
    protected $_code = 'biddyut';

    protected $_logger;
    /**
     * @var bool
     */
    protected $_isFixed = true;

    /**
     * @var \Magento\Shipping\Model\Rate\ResultFactory
     */
    protected $_rateResultFactory;


    /**
     * @var \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory
     */
    protected $_rateMethodFactory;
    protected $_apiData;
    protected $_checkoutSession;
    protected $cart;
    //protected $_customerSession;


    /**
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory
     * @param \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory
     * @param array $data
     */
     public function __construct(
         \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
         \Magento\Quote\Model\Quote\Address\RateResult\ErrorFactory $rateErrorFactory,
         \Psr\Log\LoggerInterface $logger,
         \Magento\Shipping\Model\Rate\ResultFactory $rateResultFactory,
         \Magento\Quote\Model\Quote\Address\RateResult\MethodFactory $rateMethodFactory,
         ApiData $apiData,
         CustomerCart $cart,
         \Magento\Checkout\Model\Session $checkoutSession,
       //  \Magento\Customer\Model\Session $customerSession,
         array $data = []  ) {
         $this->_rateResultFactory = $rateResultFactory;
         $this->_rateMethodFactory = $rateMethodFactory;
         $this->_logger = $logger;
         $this->_checkoutSession = $checkoutSession;
         //$this->_customerSession = $customerSession;
         $this->_apiData = $apiData;
          $this->cart = $cart;
         parent::__construct($scopeConfig, $rateErrorFactory, $logger, $data);
     }


    /**
     * @param RateRequest $request
     * @return \Magento\Shipping\Model\Rate\Result|bool
     */
    public function collectRates(RateRequest $request) {

     //\Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug('collectRates==>02200' );


        if (!$this->getConfigFlag('active')) {
          return false;
        }


        //Check Login or not
         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
         $customerSession = $objectManager->create('Magento\Customer\Model\Session');
         // if ($customerSession->isLoggedIn()) {
         //   $delivery_name= $customerSession->getCustomer()->getName();  // get  Full Name
         //   $delivery_email= $customerSession->getCustomer()->getEmail(); // get Email
         //
         // }
         // else {
         //  //Guest Checkout getFirstName
         //  $firstName=$this->_checkoutSession->getQuote()->getShippingAddress()->getFirstName();
         //  $lastName=$this->_checkoutSession->getQuote()->getShippingAddress()->getLastName();
         //  $delivery_name=$firstName.$lastName;
         //  }

          $region= $this->_checkoutSession->getQuote()->getShippingAddress()->getRegion();//Fore Zone
          $city= $this->_checkoutSession->getQuote()->getShippingAddress()->getCity();//For Zone
          $street= $this->_checkoutSession->getQuote()->getShippingAddress()->getStreet();
          $township= $this->_checkoutSession->getQuote()->getShippingAddress()->getTownship();
          if(!empty($township)){
              // Sub Category exists
              $destination=$township.','.$city; //'Demo Zone 2,Al-Rutba';
            }
            else {
              # code...
              $data = json_decode(file_get_contents('php://input'), true);
              $zone = @$data['address']['custom_attributes']['township'];
              $destination=$zone.','.$city; //'Demo Zone 2,Al-Rutba';
            }

          //$subtotal = $this->cart->getQuote()->getTotals();
          //$total=9000;//$subtotal['subtotal']['value'];
          //$fuckData = $this->cart->getQuote()->getData();
          $grandTotal = intval($this->cart->getQuote()->getBaseSubtotal());
        //  $itemsCollection = $this->cart->getQuote()->getItemsCollection();
         //\Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug(' grandTotal1== '.json_encode($grandTotal));




        /** @var \Magento\Shipping\Model\Rate\Result $result */
        $result = $this->_rateResultFactory->create();
        //If Shipping Cost Sett From Admin Config uncomment below line
        //$shippingPrice = $this->getConfigData('price');
        //If Shipping Cost Come From 3rd Party API

        //Get Courier/Shipping Methods Config Data
        $courier=$this->getConfigData('title');
        $method=$this->getConfigData('method');
        $storeid=$this->getConfigData('storeid');
        $origin=$this->getConfigData('origin');



        $category ='Bulk Product';
        $quantity=1;// Bulk Product Always 1
        $width = '0';
        $height = '0';
        $length = '0';
        //$weight = '5';        
        $weight =$this->getTotalWeight();
       // \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug('weight-->'.$weight );
        //getBiddyut($storeid,$origin,$destination,$category,$quantity,$total,$width,$height,$length,$weight)
         //\Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)
        // ->debug('CheckingCharge==>St->'.$storeid.' Ori->'.$origin.' Dest->'.$destination.' Cat->'.$category.' Qty->'.$quantity.' Price->'.$grandTotal.' Width->'.$width.' Height->'.$height.' Length->'.$length.' Weight->'.$weight );
        $shippingPrice = $this->_apiData->getBiddyut($storeid,$origin,$destination,$category,$quantity,$grandTotal,$width,$height,$length,$weight);
          //getBiddyut(method, $shippingPrice, title)
        // \Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug('ShippingPrice-->'.$shippingPrice );

         // if ($shippingPrice !== false && $shippingPrice !==0 ) {
           $method = $this->generateMethods($method, $shippingPrice, $courier);
           $result->append($method);

         // }
      //  $method = $this->generateMethods($method, $shippingPrice, $courier);
      //  $result->append($method);
      //  $method->setPrice($shippingPrice);
        // $method = $this->_rateMethodFactory->create();
        // $method->setCarrier($this->_code);
        // $method->setCarrierTitle($this->getConfigData('title'));
        // $method->setMethod($this->_code);
        // $method->setMethodTitle($this->getConfigData('name'));
        // $method->setPrice($shippingPrice);
        // $method->setCost($shippingPrice);
        // $result->append($method);

        return $result;
    }

    /**
     * @return array
     */
    public function getAllowedMethods(){
        return [$this->_code=> $this->getConfigData('name')];
    }

    public function generateMethods($service, $price, $courier){
      //\Magento\Framework\App\ObjectManager::getInstance()->get(\Psr\Log\LoggerInterface::class)->debug('Fucking Price== '.json_encode($price));

        $method = $this->_rateMethodFactory->create();
        //$method->setCarrier('flatrate');
        //$method->setMethod('flatrate');
        $method->setCarrier($this->_code);
        $method->setCarrierTitle($courier);
        $method->setMethod($this->_code);
        $method->setMethodTitle($service);
        $method->setPrice($price);
        $method->setCost($price);
        return $method;
    }
    
    public function getTotalWeight(){
        $items = $this->cart->getQuote()->getAllItems();
        $weight = 0;
        foreach($items as $item) {
            $weight += ($item->getWeight() * $item->getQty()) ;
        }

        return $weight;
      }


}
